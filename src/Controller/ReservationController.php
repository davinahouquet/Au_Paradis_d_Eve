<?php

namespace App\Controller;

use App\Entity\Espace;
use App\Form\ContactType;
use App\Entity\Reservation;
use App\Form\EvaluationType;
use App\Form\ReservationType;
use App\Repository\EspaceRepository;
use App\Services\ReservationService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{

    private $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    #[Route('/reservation', name: 'app_reservation')]
    public function index(EspaceRepository $espaceRepository): Response
    {
        $chambres = $espaceRepository->findByCategorie(1);

        return $this->render('reservation/index.html.twig', [
            'chambres' => $chambres,
        ]);
    }

    #[Route('/reservation/chambres', name: 'reservation_chambre')]
    public function afficherChambres(EntityManagerInterface $entityManager, Request $request): Response
    {
        $repository = $entityManager->getRepository(Espace::class);
        // Va trier les espaces selon la categorie 1 (Qui correspond aux chambres dans ma BDD)
        $chambres = $repository->findByCategorie(1);
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        return $this->render('home/index.html.twig', [
            'chambres' => $chambres
        ]);
    }

    // Ajouter une réservation OU modifier
    #[Route('/reservation/new/{id}', name: 'new_reservation')]
    public function newReservation( Espace $espace, Reservation $reservation = null, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository, Request $request)
    {
        if(!$this->getUser() && !$request->query->get('acceptInvited')){
            return $this->redirectToRoute('app_choix', ['id' => $espace->getId()]);
        }

        $user = $this->getUser();
        $reservation = new Reservation();
        
        if ($user){
            $email = $user->getEmail();
        }
        $chambre = $espace->getNomEspace();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            $reservation->setEspace($espace);

        // Vérifie la disponibilité de la chambre
        $message = $this->reservationService->verifierDisponibiliteChambre($espace, $reservation);
         // si le message est null, alors erreur de disponiblité
            if ($message !== null) {
                $this->addFlash('danger', $message);
                return $this->redirectToRoute('new_reservation', ['id' => $espace->getId()]);
            } else {             
                $entityManager->persist($reservation);
                $entityManager->flush();
                //Redirection vers la deuxième partie de la réservation : formulaire de coordonnées     
                $this->addFlash('message', 'Les informations ont bien été prises en compte, vous allez passer à l\'étape suivante...');
                return $this->redirectToRoute('new_coordonnees', ['reservation' => $reservation->getId()]);
            }
        }
        return $this->render('reservation/new.html.twig', [
            'form' => $form,
            'chambre' => $chambre
        ]);
    }

        // Redirige l'utilisateur vers la page de choix entre créer un compte ou poursuivre en tant qu'invité
        #[Route('/reservation/choix/{id}', name:'app_choix') ]
        public function choix(Espace $espace, EspaceRepository $espaceRepository, EntityManagerInterface $entityManager, Request $request)
        {
            return $this->render('reservation/choix.html.twig', [
                'espace' => $espace
            ]);
        }

        // Permettre à l'utilisateur de laisser un avis à la fin de son séjour
        #[Route('/reservation/evaluation/{reservation}', name:'new_evaluation') ]
        public function evaluation(Reservation $reservation = null, EntityManagerInterface $entityManager, Request $request)
        {            
            $eval = $reservation->getNote();
            if ($eval) {
                // Si un avis est déjà laissé, redirection vers une page d'erreur
                return $this->redirectToRoute('app_user');
            } else {
                $form = $this->createForm(EvaluationType::class, $reservation);
                
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                
                    $evaluation = $form->getData();
    
                    $entityManager->persist($evaluation);
                    $entityManager->flush();
    
                    return $this->redirectToRoute('app_user');
                }
            }
            return $this->render('reservation/evaluation.html.twig', [
                'form' => $form,
                'eval' => $eval
            ]);
        }

        public function disponibiliteEspace(Espace $espace, \DateTime $dateDebut, \DateTime $dateFin, EntityManagerInterface $entityManager)
        {
            $reservationRepository = $entityManager->getRepository(Reservation::class);
            // Requête en BDD pour vérifier si il y a des réservations qui se chevauchent aux dates données
            $reservationsExistantes = $reservationRepository->findEspacesReserves($espace, $dateDebut, $dateFin);
            return count($reservationsExistantes) === 0;
        }

        #[Route('/reservation/avis', name:'avis') ]
        public function toutesReservationsPasseesAvis(ReservationRepository $reservationRepository)
        {
            $toutesReservationsPassees = $reservationRepository->findToutesReservationsPassees();

            return $this->render('reservation/avis.html.twig', [
                'toutesReservationsPassees' => $toutesReservationsPassees
            ]);
        }

        #[Route('/annuler/reservation/{id}', name:'annuler_reservation')]
        public function annulerReservation(Reservation $reservation = null, EntityManagerInterface $entityManager, ReservationService $reservationService)
        {
            // Si la réservation était bien confirmée
            // if($reservation->getAdresseFacturation() !== null){
                $annulation = $this->reservationService->verifierConditionsAnnulation($reservation);

                // $reservation->setStatut($statut);
                
                $entityManager->persist($reservation);
                $entityManager->flush();

                // REGENERER FACTURE MONTANT RESTANT !!! PDF
                
            // }
            // $reservation = $this->reservation;
            // // Si 7j avant currentdate 
            // if($reservation->getDateDebutFr() > $dateLimite){
            //     $this->addFlash('message', 'Votre réservation ne peut être annulée à moins de 7 jours avant la date de début du séjour.');
            //     return $this->redirectToRoute('app_user');
            // }
            // // Si admin OU user.id = reservation.user

            $this->addFlash('message', 'Votre réservation a bien été annulée...');
            return $this->redirectToRoute('app_user');
        }

        // #[Route('/suppression/reservations/nonConfirmees', name:'suppression_reservations_non_confirmees')]
        // public function suppressionReservationsNonConfirmees(Reservation $reservation)
        // {

        // }
}
