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
            $reservation->setEspace($espace);

            // Calcul du prix total
            $prixTotal = $reservation->calculerPrixTotal();

            //Trouver les dates de début et de fin de la réservation en cours
            $dateDebutNlleReservation = $reservation->getDateDebut();
            $dateFinNlleReservation = $reservation->getDateFin();

            //Trouver les espaces réservées aux dates sélectionnées grâce à la requête DQL créée dans ReservationRepository
            $indisponible = $reservationRepository->findEspacesReserves($espace, $dateDebutNlleReservation, $dateFinNlleReservation);

                        //rajouter condition pas de réservation à l'envers
                        //genre dateDebutNlleReservation < dateFinNlleReserv
                        //Toutes les conditions nécessaires pour poursuivre la réservation :
                        
                        
            if($dateDebutNlleReservation > $dateFinNlleReservation){
                $this->addFlash('message', 'La date de fin de votre séjour doit être supérieure à sa date de début.');
                return $this->redirectToRoute('app_espace');
            } elseif($indisponible){ //Pas de chevauchement de réservation
                $this->addFlash('message', 'La réservation se chevauche avec une réservation existante. Veuillez choisir d\'autres dates.');
                return $this->redirectToRoute('app_home');
            } elseif($reservation->getDateDebut() <= $currentDate){ //Pas de réservation dans le passé, ni au jour même
                $this->addFlash('message', 'Merci de réserver au moins un jour avant le début du séjour');
                return $this->redirectToRoute('app_home');
            } elseif($reservation->getDuree() < 2 ){  //Minimum 2 jours pour une réservation
                $this->addFlash('message', 'La réservation doit être de deux nuits minimum');
                return $this->redirectToRoute('app_espace');
            } elseif($reservation->getDuree() > 28 ) { //Maximum 28 jours pour une réservation
                $this->addFlash('message', 'La réservation ne doit pas excéder 28 jours');
                return $this->redirectToRoute('app_espace');
            } elseif($reservation->getNbPersonnes() > $espace->getNbPlaces()) { //Le nombre de personnes dans la réservation ne doit pas excéder le nombre de places dans la chambre (hors enfants)
                $this->addFlash('message', 'Nombre de personnes trop élevé. Merci de réserver une autre chambre pour les personnes supplémentaires. (Hors enfants)');
                return $this->redirectToRoute('app_espace');
            } else { //Si toutes les conditions sont réunies, alors on peut poursuivre la réservation et insérer les champs en base de données
                $reservation->setPrixTotal($prixTotal); //Il faut set les champs non nullables. Calculé grâce à la fonction calculerPrixTotal dans l'entité Reservation, en fonction de la durée du séjour
                $reservation->setEmail($email);
                $reservation->setAdresseFacturation($adresseFacturation);
                $reservation->setFacture($facture);
                $reservation->setDateReservation($currentDate);
                
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
