<?php

namespace App\Services;

use App\Entity\Reservation;
use App\Model\ReservationOption;
use Symfony\Component\Serializer\SerializerInterface;

class ReservationService
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getAllOptionsFromJson(): array
    {
        // Les options
        $optionsFile = file_get_contents('../public/json/options.json');

        $optionsData = json_decode($optionsFile, true);
        $tableauOptions = [];

        foreach ($optionsData as $option) {
            $idOption = $option['id'];
            unset($option['id']);
            $tableauOptions[$idOption] = $option;
        }
        

        return $tableauOptions;
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
                    $options = $this->getAllOptionsFromJson();
                    foreach ($reservation->getOptions() as $idOption) {
                        // Vérifiez si l'option existe dans les tarifs
                        if (isset($options[$idOption])) {
                            // Ajoutez le tarif de l'option au prix total
                            $prixTotal += $options[$idOption]['tarif'];
                        }
                    }
                }
                return $prixTotal;
            }
        }
    }
}