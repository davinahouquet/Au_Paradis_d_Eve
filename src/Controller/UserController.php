<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Espace;
use App\Form\UserType;
use App\Entity\Booking;
use App\Entity\Reservation;
use App\Form\EmailFormType;
use App\Form\CoordonneesType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Form\ChangePasswordFormType;
use App\Services\ReservationService;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\EventSubscriber\CalendarSubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerLcJPPOG\getBookingRepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\mailer;
use Symfony\Component\Mailer\Mailer as MailerMailer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    private $reservationService;
    private $bookingEvent;
    private $mailer;

    public function __construct(ReservationService $reservationService, CalendarSubscriber $bookingEvent, MailerInterface $mailer)
    {
        $this->reservationService = $reservationService;
        $this->bookingEvent = $bookingEvent;
        $this->mailer = $mailer;
    }
    
    #[Route('/user', name: 'app_user')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();
        $currentDate = new \DateTime();
        $reservationsNonConfirmees = $reservationRepository->findReservationsNonConfirmees($user);

        return $this->render('user/index.html.twig', [
            'reservationsNonConfirmees' => $reservationsNonConfirmees,
            'currentDate' => $currentDate,
        ]);
    }

    // Deuxième partie du formulaire pour remplir le formulaire et passer le statut de la réservation à CONFIRMEE
    #[Route ('/user/coordonnees/{reservation}', name:'new_coordonnees')]
    public function new_coordonnees(Reservation $reservation,  User $user = null, Espace $espace = null, EntityManagerInterface $entityManager, Request $request, ReservationRepository $reservationRepository, MailerInterface $mailer): Response
    {
            // Afficher toutes les infos de la chambre qu'on réserve
            $espace = $reservation->getEspace();
            $statut = 'CONFIRMEE';

            //Insérer les heures de check-in et check-out
            $checkIn = 15;
            $checkOut= 11;

            //Trouver les dates de début et de fin de la réservation en question -> Trouver une meilleure façon si possible
            $reservation->setDateDebut((new \DateTime($reservation->getDateDebut()->format("d-m-Y")))->setTime($checkIn, 0, 0));
            $reservation->setDateFin((new \DateTime($reservation->getDateFin()->format("d-m-Y")))->setTime($checkOut, 0, 0));

            $reservation->setStatut($statut);
            
            $entityManager->persist($reservation);
            $entityManager->flush();

            $dateDebut = $reservation->getDateDebut();
            $dateFin = $reservation->getDateFin();
            
            // On Revérifie ici si réservation (pas que qqun ait réservé entre temps)
            $indisponible = $reservationRepository->findEspacesReserves($espace, $dateDebut, $dateFin);
            
            if($indisponible){
                $this->addFlash('message', 'La réservation n\'est plus disponible aux dates séléctionnées');
                return $this->redirectToRoute('show_espace', ['id' => $espace->getId()]);
                exit;
            }
             // //la date du jour
            date_default_timezone_set('Europe/Paris');
            $currentDate = new \Datetime();
            $facture = 'app_pdf_generator';
             // Calcul du prix total
            $prixTotal = $this->reservationService->calculerPrixTotal($reservation);
                
            //Création du formulaire de coordonnées
            $form = $this->createForm(CoordonneesType::class);
            $form->handleRequest($request);
            
            // if($this->getUser()){
            //     //On pointe (get) les champs qu'on veut préremplir et on y insère (set) les valeurs souhaitées
            //     $form->get('email')->setData($this->getUser()->getEmail());
            //     $form->get('adresse')->setData($this->getUser()->getAdresse());
            //     $form->get('cp')->setData($this->getUser()->getCp());
            //     $form->get('ville')->setData($this->getUser()->getVille());
            //     $form->get('pays')->setData($this->getUser()->getPays());
            // }

            if ($form->isSubmitted() && $form->isValid()) {
                
                $formData = $form->getData();
                
                // On ne manipule plus d'objet mais bien un tableau associatif
                $email = $formData['email'];
                $adresse = $formData['adresse'];
                $cp = $formData['cp'];
                $ville = $formData['ville'];
                $pays = $formData['pays'];
                $souvenir = $formData['souvenir'];
                
                //Définir l'adresse de facturation grâce aux données récupérées dans le formulaire
                $adresseFacturation = $adresse.' '.$cp." ".$ville.' '.$pays;
                $reservation->setAdresseFacturation($adresseFacturation);
                $reservation->setEmail($email);
                $reservation->setDateReservation($currentDate);
                $reservation->setPrixTotal($prixTotal);
                $reservation->setFacture($facture);

                // Pour mettre la réservation dans le calendrier
                // $bookingEvent->onCalendarSetData($reservation);

                //Définir l'user en session
                $user = $this->getUser();

                //si il y a bien un user connecté, et que la checkbox a été cochée
                if($user){
                    $reservation->setUser($user);
                    if($souvenir == true){
                        //Alors on set les informations du formulaire dans la table user
                        $user->setAdresse($adresse);
                        $user->setCp($cp);
                        $user->setVille($ville);
                        $user->setPays($pays);

                        $entityManager->persist($user);
                        $entityManager->flush();
                    }
                }

                //Et également dans la table réservation (via l'adresse de facturation)
                $entityManager->persist($reservation);
                $entityManager->flush();

                $emailReservation = $reservation->getEmail();
                $prenom = $reservation->getPrenom();
        
                $email = (new Email())
                    ->from('admin@auparadisdeve.fr')
                    ->to($emailReservation)
                    ->subject('Au Paradis d\'Eve - Votre réservation!')
                    ->html('<p>See Twig integration for better HTML integration!</p>');
        
                $mailer->send($email);

                
                $this->addFlash('message', 'La réservation a bien été prise en compte, veuillez trouver toutes les informations nécessaires dans votre boîte mail');
                if($user){
                    return $this->redirectToRoute('reservations_a_venir');
                } else {
                    return $this->redirectToRoute('app_home');
                }
            }
        return $this->render('reservation/coordonnees.html.twig', [
            'form' => $form,
            'espace' => $espace,
            'reservation' => $reservation
        ]);
    }

    #[Route('/user/delete/{id}', name: 'delete_user')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Supprime l'utilisateur de la base de données
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'L\'utilisateur a été supprimé avec succès'
        );

        return $this->redirectToRoute('app_register');
    }

    #[Route('/user/edit/pseudo/{id}', name: 'edit_user')]
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager) : Response
    {
        
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
    
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées'
            ); 
            return $this->redirectToRoute('app_user', ['id' => $user->getId()]);
        }


        return $this->render('user/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/user/editUserEmail/{id}', name: 'edit_user_email')]
    public function editEmail(User $user, Request $request, EntityManagerInterface $entityManager) : Response
    {
        // Si l'user n'existe pas redirection à home
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $email = $this->getUser()->getEmail();

        $form = $this->createForm(EmailFormType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées'
            ); 
            return $this->redirectToRoute('app_user', ['id' => $user->getId()]);
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form
        ]);
    }
    
    #[Route('/user/upgradePassword', name: 'upgrade_password')]
    public function upgradePassword(UserPasswordHasherInterface $userPasswordHasher, Request $request, EntityManagerInterface $entityManager) : Response
    {
        // Si l'user n'existe pas redirection à la page de connexion
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $password = $this->getUser()->getPassword();
        //condition si l'ancien mdp correspond

        $form = $this->createForm(ChangePasswordFormType::class);

        $form->handleRequest($request);
        
            if($form->isSubmitted() && $form->isValid()){
                $user =  $this->getUser();
                $plainPassword = $userPasswordHasher->hashPassword($user, $form->getData());

                $user->setPassword($plainPassword);
                $entityManager->persist($user);
                $entityManager->flush();

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées'
            ); 
            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form
        ]);
    }
    
    #[Route('/user/reservations/enCours', name: 'reservations_en_cours')]
    public function reservationsEnCoursDirectory(ReservationRepository $reservationRepository, User $user)
    {
        $toutesReservationsEnCours = $reservationRepository->findToutesReservationsEnCours();
        $user = $this->getUser();
        $reservationEnCours = $reservationRepository->findReservationEnCours($user);
        
        return $this->render('user/reservations/en_cours.html.twig', [
            'reservationEnCours' => $reservationEnCours,
            'toutesReservationsEnCours' => $toutesReservationsEnCours
        ]);
    }

    #[Route('/user/reservations/passees', name: 'reservations_passees')]
    public function reservationsPasseesDirectory(ReservationRepository $reservationRepository, User $user)
    {
        $user = $this->getUser();
        $reservationsPassees = $reservationRepository->findReservationsPassees($user);
        $toutesReservationsPassees = $reservationRepository->findToutesReservationsPassees();

        return $this->render('user/reservations/passees.html.twig', [
            'reservationsPassees' => $reservationsPassees,
            'toutesReservationsPassees' => $toutesReservationsPassees
        ]);
    }

    #[Route('/user/reservations/annulees', name: 'reservations_annulees')]
    public function reservationsAnnulees(ReservationRepository $reservationRepository, User $user)
    {
        $user = $this->getUser();
        $reservationsAnnulees = $reservationRepository->findReservationsAnnulees($user);
        
        return $this->render('user/reservations/annulees.html.twig', [
            'reservationsAnnulees' => $reservationsAnnulees
        ]);
    }
    
    #[Route('/user/reservations/aVenir', name: 'reservations_a_venir')]
    public function reservationsAVenirDirectory(ReservationRepository $reservationRepository, User $user)
    {
        $user = $this->getUser();
        if($user){
            $reservationsAVenir = $reservationRepository->findReservationsAVenir($user);
        } else {
            $reservationsAVenir = [];
        }
        $toutesReservationsAVenir = $reservationRepository->findToutesReservationsAVenir();
        
        return $this->render('user/reservations/a_venir.html.twig', [
            'reservationsAVenir' => $reservationsAVenir,
            'toutesReservationsAVenir' => $toutesReservationsAVenir
        ]);
    }

    #[Route('/user/reservations/nonConfirmees', name: 'reservations_non_confirmees')]
    public function reservationsnonConfirmees(ReservationRepository $reservationRepository, User $user)
    {
        $user = $this->getUser();
        $reservationsNonConfirmees = $reservationRepository->findReservationsNonConfirmees($user);
        
        return $this->render('user/reservations/non_confirmees.html.twig', [
            'reservationsNonConfirmees' => $reservationsNonConfirmees,
        ]);
    }
    
    #[Route('/user/parametres', name: 'parametres')]
    public function reservationsDirectory()
    {
        return $this->render('user/parametres.html.twig');
    }

    #[Route('/user/questions/pratiques', name: 'questions_pratiques')]
    public function questionsPratiques()
    {
        $jsonData = file_get_contents('../public/json/questions_pratiques.json');
        $data = json_decode($jsonData, true);

        return $this->render('user/questions_pratiques.html.twig', [
            'data' => $data
        ]);
    }

    public function disponibiliteEspace(Espace $espace, \DateTime $dateDebut, \DateTime $dateFin, EntityManagerInterface $entityManager)
    {
        $reservationRepository = $entityManager->getRepository(Reservation::class);
        // Requête en BDD pour vérifier si il y a des réservations qui se chevauchent aux dates données
        $reservationsExistantes = $reservationRepository->findEspacesReserves($espace, $dateDebut, $dateFin);
        return count($reservationsExistantes) === 0;
    }
}
