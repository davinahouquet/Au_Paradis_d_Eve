<?php

namespace App\Services;

use App\Entity\Espace;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;

class ReservationService
{

    private $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function verifierDisponibiliteChambre(Espace $espace, Reservation $reservation): ?string
    {
        //Trouver les dates de début et de fin de la réservation en cours
        $dateDebutNlleReservation = $reservation->getDateDebut();
        $dateFinNlleReservation = $reservation->getDateFin();

        //Trouver les espaces réservées aux dates sélectionnées grâce à la requête DQL créée dans ReservationRepository
        $indisponible = $this->reservationRepository->findEspacesReserves($espace, $dateDebutNlleReservation, $dateFinNlleReservation);

        // La date du jour
        date_default_timezone_set('Europe/Paris');                       
        $currentDate = new \Datetime();
        
        //Toutes les conditions nécessaires pour poursuivre la réservation      
        if($dateDebutNlleReservation > $dateFinNlleReservation){ //La date de début du séjour doit être supérieure à sa date de fin
            $message = 'La date de fin de votre séjour doit être supérieure à sa date de début.';
        } elseif($indisponible){ //Pas de chevauchement de réservation
            $message = 'La réservation se chevauche avec une réservation existante. Veuillez choisir d\'autres dates.';
        } elseif($reservation->getDateDebut() <= $currentDate){ //Pas de réservation dans le passé, ni au jour même
            $message = 'Merci de réserver au moins un jour avant le début du séjour';
        } elseif($reservation->getDuree() < 2 ){  //Minimum 2 jours pour une réservation
            $message = 'La réservation doit être de deux nuits minimum';
        } elseif($reservation->getDuree() > 28 ) { //Maximum 28 jours pour une réservation
            $message = 'La réservation ne doit pas excéder 28 jours';
        } elseif($reservation->getNbPersonnes() > $espace->getNbPlaces()) { //Le nombre de personnes dans la réservation ne doit pas excéder le nombre de places dans la chambre (hors enfants)
            $message = 'Nombre de personnes trop élevé. Merci de réserver une autre chambre pour les personnes supplémentaires. (Hors enfants)';
        }
        return $message ?? null;
    }

    public function calculerPrixTotal(Reservation $reservation): ?float
    {
        if ($reservation->getEspace() && $reservation->getDateDebut() && $reservation->getDateFin()) {

            $duree = $reservation->getDuree();
            // Vérifiez que l'objet Espace est défini
            if ($reservation->getEspace()) {
                $prixChambre = $reservation->getEspace()->getPrix();
                $prixTotal = $prixChambre * $duree;

                if($reservation->getOptions() !== null){
                    foreach ($reservation->getOptions() as $options) {
                        // Ajoutez le tarif de l'option au prix total
                        $prixTotal += $options->getTarif();
                    }
                }
                return $prixTotal;
            }
        }
    }

    public function verifierConditionsAnnulation(Reservation $reservation)
    {
        $dateDebutReservation = $reservation->getDateDebut();
        $datePlusTrenteJours = date("d-m-Y", strtotime("+30 days"));
        $datePlusVingtJours = date("d-m-Y", strtotime("+20 days"));
        $datePlusSeptJours = date("d-m-Y", strtotime("+7 days"));

        if($_SESSION['user']['role'] = 'ROLE_ADMIN'){
            // [ A REMBOURSER ] Si l’annulation intervient moins de 20jours avant la date d’arrivée,  30 %  du total de la réservation est conservé.
            $message = 'Le montant versé lors de la réservation sera remboursé';
            $reservation->setStatut('A REMBOURSER');
        }

        if($datePlusTrenteJours < $dateDebutReservation){
            // [ A REMBOURSER ] Si l’annulation intervient moins de 20jours avant la date d’arrivée,  30 %  du total de la réservation est conservé.
            $message = 'Le montant versé lors de la réservation sera remboursé';
            $reservation->setStatut('A REMBOURSER');
        }
        elseif($datePlusVingtJours > $dateDebutReservation){
            // [A REMBOURSER PARTIELLEMENT] Si l'annulation intervient moins de 20 jours avant la date d'arrivée, l'établissement se réserve le droit de conserver 30% du total de la réservation.
            $message = 'Remboursement partiel, 30 %  du total de la réservation est conservé';
            $reservation->setStatut('A REMBOURSER PARTIELLEMENT');
        }
        elseif($datePlusSeptJours > $dateDebutReservation){
            // Si l’annulation intervient moins de 7 jours avant la date d’arrivée, ou en cas de non présentation, ou si le client écourte son séjour, l'établissement se réserve le droit de facturer et de réclamer ou prélever le montant total du séjour prévu y compris la réservation de prestations annexes commandées (table d’hôtes,…).
            $message = 'Annulation impossible, merci de contacter l\'établissement';
            $reservation->setStatut('ANNULEE');
        }
        
        return $message ?? null;

    }
}