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
    
    #[Route('/direction/{id}', name: 'app_direction')]
    public function direction(Espace $espace, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_choix', ['id' => $espace->getId()]);
        } else {
            return $this->redirectToRoute('new_reservation', ['id' => $espace->getId()]);
        }
    }

    private function calculerPrixOptions($selectedOptions)
    {
        $optionsFile = file_get_contents('../public/json/options.json');
        $optionsData = json_decode($optionsFile, true);
    
        $prixTotalOptions = 0;
    
        foreach ($selectedOptions as $selectedOption) {
            foreach ($optionsData['options_chambres_hotes'] as $optionData) {
                if ($optionData['id_option'] == $selectedOption) {
                    $prixTotalOptions += $optionData['tarif_option'];
                    break;
                }
            }
        }
    
        return $prixTotalOptions;
    }
    
    // Ajouter une réservation OU modifier
    #[Route('/reservation/new/{id}', name: 'new_reservation')]
    public function newReservation( Espace $espace, Reservation $reservation = null, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository, Request $request)
    {
        $user = $this->getUser();

        $reservation = new Reservation();
        
        if ($user){
            $email = $user->getEmail();
        }

        $chambre = $espace->getNomEspace();
        
        $options = $this->reservationService->getAllOptionsFromJson();
    
        $form = $this->createForm(ReservationType::class, $reservation, ['options' => $options]);
        // $form = $this->createForm(ReservationType::class, $reservation);
            
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            
            $reservation = $form->getData();
            $reservation->setEspace($espace);

            //Trouver les dates de début et de fin de la réservation en cours
            $dateDebutNlleReservation = $reservation->getDateDebut();
            $dateFinNlleReservation = $reservation->getDateFin();

            //Trouver les espaces réservées aux dates sélectionnées grâce à la requête DQL créée dans ReservationRepository
            $indisponible = $reservationRepository->findEspacesReserves($espace, $dateDebutNlleReservation, $dateFinNlleReservation);

            //Toutes les conditions nécessaires pour poursuivre la réservation      
                    // //la date du jour
            date_default_timezone_set('Europe/Paris');                       
            $currentDate = new \Datetime();
            if($dateDebutNlleReservation > $dateFinNlleReservation){ //La date de début du séjour doit être supérieure à sa date de fin
                $this->addFlash('message', 'La date de fin de votre séjour doit être supérieure à sa date de début.');
                return $this->redirectToRoute('show_espace', ['id' => $espace->getId()]);
            } elseif($indisponible){ //Pas de chevauchement de réservation
                $this->addFlash('message', 'La réservation se chevauche avec une réservation existante. Veuillez choisir d\'autres dates.');
                return $this->redirectToRoute('show_espace', ['id' => $espace->getId()]);
            } elseif($reservation->getDateDebut() <= $currentDate){ //Pas de réservation dans le passé, ni au jour même
                $this->addFlash('message', 'Merci de réserver au moins un jour avant le début du séjour');
                return $this->redirectToRoute('show_espace', ['id' => $espace->getId()]);
            } elseif($reservation->getDuree() < 2 ){  //Minimum 2 jours pour une réservation
                $this->addFlash('message', 'La réservation doit être de deux nuits minimum');
                return $this->redirectToRoute('show_espace', ['id' => $espace->getId()]);
            } elseif($reservation->getDuree() > 28 ) { //Maximum 28 jours pour une réservation
                $this->addFlash('message', 'La réservation ne doit pas excéder 28 jours');
                return $this->redirectToRoute('show_espace', ['id' => $espace->getId()]);
            } elseif($reservation->getNbPersonnes() > $espace->getNbPlaces()) { //Le nombre de personnes dans la réservation ne doit pas excéder le nombre de places dans la chambre (hors enfants)
                $this->addFlash('message', 'Nombre de personnes trop élevé. Merci de réserver une autre chambre pour les personnes supplémentaires. (Hors enfants)');
                return $this->redirectToRoute('show_espace', ['id' => $espace->getId()]);
            } else { //Si toutes les conditions sont réunies, alors on peut poursuivre la réservation et insérer les champs en base de données
            // Set options dans reservation
            $selectedOptions = $form->get('options')->getData();
            if (is_array($selectedOptions)) {
                $reservation->setOptions($selectedOptions);

                // Calculer le prix total des options et l'assigner à l'entité Reservation
                // $prixTotalOptions = $this->calculerPrixOptions($selectedOptions);
                // $reservation->setPrixOptions($prixTotalOptions);
            }
                $entityManager->persist($reservation);
                $entityManager->flush();
                
                //Redirection vers la deuxième partie de la réservation : formulaire de coordonnées     
                $this->addFlash('message', 'Les informations ont bien été prises en compte, vous allez passer à l\'étape suivante...');
                return $this->redirectToRoute('new_coordonnees', ['reservation' => $reservation->getId()]);
            }
        }  
            return $this->render('reservation/new.html.twig', [
                'form' => $form,
                'chambre' => $chambre,
                'options' => $options
            ]);
        }

        #[Route('/reservation/choix/{id}', name:'app_choix') ]
        public function choix(Espace $espace, EspaceRepository $espaceRepository, EntityManagerInterface $entityManager, Request $request)
        {
            return $this->render('reservation/choix.html.twig', [
                'espace' => $espace
            ]);
        }

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
}
