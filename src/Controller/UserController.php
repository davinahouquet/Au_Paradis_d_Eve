<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Espace;
use App\Entity\Reservation;
use App\Form\CoordonneesType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    
    #[Route ('/user/coordonnees/{reservation}', name:'new_coordonnees')]
    public function new_coordonnees(Reservation $reservation,  User $user = null, Espace $espace = null, EntityManagerInterface $entityManager, Request $request, ReservationRepository $reservationRepository): Response
    {
            // Afficher toutes les infos de la chambre qu'on réserve
            $espace = $reservation->getEspace();
            // dd($espace);
            // dump($reservation);
            //Insérer les heures de check-in et check-out
            $checkIn = 15;
            $checkOut= 11;
            //Trouver les dates de début et de fin de la réservation en question
            $reservation->setDateDebut((new \DateTime($reservation->getDateDebut()->format("d-m-Y")))->setTime($checkIn, 0, 0)); //ok
            $reservation->setDateFin((new \DateTime($reservation->getDateFin()->format("d-m-Y")))->setTime($checkOut, 0, 0)); //ok
            $entityManager->persist($reservation);
            $entityManager->flush();

            // dd($reservation);

            $dateDebut = $reservation->getDateDebut();
            $dateFin = $reservation->getDateFin();
            // On REvérifie ici si réservation (pas que qqun ait réservé entre temps)
            $indisponible = $reservationRepository->findEspacesReserves($espace, $dateDebut, $dateFin);
            
            if($indisponible){
                $this->addFlash('message', 'La réservation n\'est plus disponible aux dates séléctionnées');
                return $this->redirectToRoute('show_espace', ['id' => $espace->getId()]);
                exit;
            }
             // //la date du jour
            date_default_timezone_set('Europe/Paris');
            $currentDate = new \Datetime();
            // dd($currentDate);

            $facture ='lien.pdf'; //lien vers le pdf

             // Calcul du prix total
             $prixTotal = $reservation->calculerPrixTotal();
                
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

                //Définir l'user en session
                $user = $this->getUser();
                // $id = $user->getId();
                // dd($id);
                //si il y a bien un user connecté, et que la checkbox a été cochée
                if($user){
                    if($souvenir == true){
                        //Alors on set les informations du formulaire dans la table user
                        $user->setAdresse($adresse);
                        $user->setCp($cp);
                        $user->setVille($ville);
                        $user->setPays($pays);

                        $reservation->setUser($user);

                        $entityManager->persist($user);
                        $entityManager->flush();
                    }
                }


                //Et également dans la table réservation (via l'adresse de facturation)
                $entityManager->persist($reservation);
                $entityManager->flush();
                // dd($reservation);
                $this->addFlash('message', 'La réservation a bien été prise en compte');
                return $this->redirectToRoute('app_home');
            }
        return $this->render('reservation/coordonnees.html.twig', [
            'form' => $form,
            'espace' => $espace,
            'reservation' => $reservation
        ]);
    }

    #[Route('/user/{id}', name: 'app_user')]
    public function profil(User $user, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneBy([]);
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    //TEST
    public function disponibiliteEspace(Espace $espace, \DateTime $dateDebut, \DateTime $dateFin, EntityManagerInterface $entityManager)
    {
        $reservationRepository = $entityManager->getRepository(Reservation::class);
        // Requête en BDD pour vérifier si il y a des réservations qui se chevauchent aux dates données
        $reservationsExistantes = $reservationRepository->findEspacesReserves($espace, $dateDebut, $dateFin);
        return count($reservationsExistantes) === 0;
    }
}
