<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Espace;
use App\Entity\Reservation;
use App\Form\CoordonneesType;
use App\Form\ReservationType;
use App\Repository\EspaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    
    // rajouter un {id} dans cette route
    #[Route('/reservation/new/{id}', name: 'new_reservation')]
    public function newReservation(Reservation $reservation, Espace $espace, EntityManagerInterface $entityManager, Request $request)
    {
        $email = 'test@test';
        $adresseFacturation = 'test'; // on enchainera adresse/ville/cp du user ici
        $facture ='test'; //lien vers le pdf

        $chambre = $espace->getNomEspace();


        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $reservation = $form->getData();

            // Calcul du prix total
            $prixTotal = $reservation->calculerPrixTotal();
            $reservation->setPrixTotal($prixTotal);
            
            // setEmail (il faut set les champs non nullables)
            $reservation->setEmail($email);
            $reservation->setEspace($espace);
            $reservation->setAdresseFacturation($adresseFacturation);
            $reservation->setFacture($facture);

            // Si connecté on set le user

            // Sinon, on redirgie sur une page de CHOIX (réserver en tant qu'invité, ou se connecter)
            // return $this->redirectToRoute('app_choix');

            $entityManager->persist($reservation);
            $entityManager->flush();
    
            $this->addFlash('message', 'Les informations ont bien été prises en compte, vous allez passer à l\'étape suivante...');
            return $this->redirectToRoute('new_coordonnees', ['reservation' => $reservation->getId()]);
        }
    
            return $this->render('reservation/new.html.twig', [
                'form' => $form,
                'chambre' => $chambre
            ]);
        }

        // Ajouter les coordonnées dans la deuxième étape de la réservation
        #[Route('/reservation/coordonnees/{reservation}', name: 'new_coordonnees')]
        public function coordonnees(Reservation $reservation, Espace $espace = null, EntityManagerInterface $entityManager, Request $request)
        {

            $espace = $reservation->getEspace();
            // Afficher toutes les infos de la réservation
            $chambre = $espace->getNomEspace();

            // Formulaire des coordonnées
            $form = $this->createForm(CoordonneesType::class);




            $this->addFlash('message', 'La réservation a bien été prise en compte');
            return $this->redirectToRoute('app_home');
            // Redirigera vers le récap de la réservation (si paiement sur la page de paiement)

            return $this->render('reservation/coordonnees.html.twig', [
                'form' => $form,
                'chambre' => $chambre
            ]);
        }

        // #[Route('/reservation/choix')]
        // public function choix()
        // {

        // }
}
