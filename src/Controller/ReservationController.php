<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Espace;
use App\Entity\Reservation;
use App\Form\CoordonneesType;
use App\Form\ReservationType;
use App\Repository\EspaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(EspaceRepository $espaceRepository): Response
    {

        $chambres = $espaceRepository->findByCategorie(1);

        return $this->render('reservation/index.html.twig', [
            'chambres' => $chambres,
        ]);
    }
    
    #[Route('/direction/{id}', name: 'app_direction')]
    public function direction(Espace $espace, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_choix', ['id' => $espace->getId()]);
        } else {
            return $this->redirectToRoute('new_reservation', ['id' => $espace->getId()]);
        }
    }


    // Ajouter une réservation OU modifier
    #[Route('/reservation/new/{id}', name: 'new_reservation')]
    public function newReservation( Espace $espace, Reservation $reservation = null, User $user, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository, Request $request)
    {
        $reservation = new Reservation();
        
        $email = $user->getEmail();

        if(!$user->getAdresse()){
            $adresseFacturation = "Null";
        } else {
            $adresseFacturation = $user->getAdresse()." ".$user->getCp()." ".$user->getVille()." ".$user->getPays();
        }
        $facture ='lien.pdf'; //lien vers le pdf

        $chambre = $espace->getNomEspace();
        
        // //la date du jour
        date_default_timezone_set('Europe/Paris');
        $currentDate = new \Datetime();
        // dd($currentDate);

        $form = $this->createForm(ReservationType::class, $reservation);
            
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            
            $reservation = $form->getData();

            // // dd( $dateFinNlleReservation); -> Nouvelle date prise en compte
            // // dd($dateDebutNlleReservation); -> Nouvelle date prise en compte
            
            // //Je créé un tableau pour les reservations futures
            // $reservationsFutures = new ArrayCollection();
            
            // $chevauchement = false; //booléen qui permettra de dire si correspondance entre les dates (si faux, on continue la réservation)
            // //J'y insère uniquement les réservations FUTURES
            // $reservations = $espace->getReservations();
            // foreach($reservations as $resa){
            //     if ($resa->getDateDebut() > $currentDate) {
                //         // La réservation est future, on l'zjoute à la collection de réservations futures
                //         $reservationsFutures->add($reservation);
                //     }
                //     if($resa->getDateFin() > $dateDebutNlleReservation && $resa->getDateDebut() <= $dateFinNlleReservation()) {
                    //         // dd($resa->getDateDebut());
                    //         // Les dates se chevauchent, définir le booléen sur true
                    //         $chevauchement = true;
                    //         break; // Sortir de la boucle dès qu'une correspondance est trouvée
                    //     }
                    // } 
                    // //Si chevauchement = message d'erreur puis redirection
                    // if($chevauchement) {
                        //     $this->addFlash('message', 'La réservation se chevauche avec une réservation existante. Veuillez choisir d\'autres dates.');
                        //     return $this->redirectToRoute('app_home');
            // }
            
            
            $reservation->setEspace($espace);
            // Calcul du prix total
            $prixTotal = $reservation->calculerPrixTotal();
            $dateDebutNlleReservation = $reservation->getDateDebut();
            $dateFinNlleReservation = $reservation->getDateFin();

            $estDisponible = $reservationRepository->findEspacesReserves($espace, $dateDebutNlleReservation, $dateFinNlleReservation);

            if(!$estDisponible){
                $this->addFlash('message', 'La réservation se chevauche avec une réservation existante. Veuillez choisir d\'autres dates.');
                return $this->redirectToRoute('app_home');
            } elseif($reservation->getDateDebut() <= $currentDate){
                $this->addFlash('message', 'Merci de réserver au moins un jour avant le début du séjour');
                return $this->redirectToRoute('app_home');
            } elseif($reservation->getDuree() < 2 ){
                //Minimum de jours pour une réservation
                $this->addFlash('message', 'La réservation doit être de deux nuits minimum');
                return $this->redirectToRoute('app_espace');
            } elseif($reservation->getDuree() > 28 ) {
                //Maximum de jours pour une réservation
                $this->addFlash('message', 'La réservation ne doit pas excéder 28 jours');
                return $this->redirectToRoute('app_espace');

            } elseif($reservation->getNbPersonnes() > $espace->getNbPlaces()) {
                //Vérifier que le nb de personnes dans la réservation == nbPlaces de la chambre
                $this->addFlash('message', 'Nombre de personnes trop élevé. Merci de réserver une autre chambre pour les personnes supplémentaires. (Hors enfants)');
                return $this->redirectToRoute('app_espace');
            } else {
                $reservation->setPrixTotal($prixTotal);
                
                // Il faut set les champs non nullables
                $reservation->setEmail($email);
                $reservation->setAdresseFacturation($adresseFacturation);
                $reservation->setFacture($facture);
                $reservation->setDateReservation($currentDate);
                
                $entityManager->persist($reservation);
                $entityManager->flush();
                
                //Si non connecté redirection page de CHOIX (réserver en tant qu'invité, ou se connecter)       
                $this->addFlash('message', 'Les informations ont bien été prises en compte, vous allez passer à l\'étape suivante...');
                return $this->redirectToRoute('new_coordonnees', ['reservation' => $reservation->getId()]);
            }
        }  
            return $this->render('reservation/new.html.twig', [
                'form' => $form,
                'chambre' => $chambre
            ]);
        }

        #[Route('/reservation/choix/{id}', name:'app_choix') ]
        public function choix(Espace $espace, EspaceRepository $espaceRepository, EntityManagerInterface $entityManager, Request $request)
        {
            return $this->render('reservation/choix.html.twig', [
                'espace' => $espace
            ]);
        }

        public function disponibiliteEspace(Espace $espace, \DateTime $dateDebut, \DateTime $dateFin, EntityManagerInterface $entityManager)
        {
            $reservationRepository = $entityManager->getRepository(Reservation::class);
            // Query the database to check if there are any bookings that overlap
            // with the provided dates for the given room.
            $reservationsExistantes = $reservationRepository->findEspacesReserves($espace, $dateDebut, $dateFin);
            return count($reservationsExistantes) === 0;
        }
}
