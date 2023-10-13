<?php

namespace App\Controller;

use App\Entity\Espace;
use App\Form\ReservationType;
use App\Repository\EspaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Reservation;

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
    // rajouter un {id} dans cette route
    #[Route('/reservation/new/{id}', name: 'new_reservation')]
    public function newReservation(Espace $espace, EntityManagerInterface $entityManager, Request $request)
    {

        $chambre = $espace->getNomEspace();

        $reservation = new Reservation();
        $reservation->setEspace($espace);

        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $reservation = $form->getData();

            // Calcul du prix total
            $prixTotal = $reservation->calculerPrixTotal();
            $reservation->setPrixTotal($prixTotal);
            
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            $this->addFlash('message', 'La réservation a bien été prise en compte');
            return $this->redirectToRoute('app_reservation');
            // Redirigera vers le récap de la réservation (si paiement sur la page de paiement)
        }
    
            return $this->render('reservation/new.html.twig', [
                'form' => $form,
                'chambre' => $chambre
            ]);
        }
}
