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
    
    // Ajouter une réservation OU modifier
    #[Route('/reservation/new/{id}', name: 'new_reservation')]
    public function newReservation(Reservation $reservation, Espace $espace, User $user, EntityManagerInterface $entityManager, Request $request)
    {
        $email = $user->getEmail();
        $adresseFacturation = $user->getAdresse(); // on enchainera adresse/ville/cp du user ici
        $facture ='lien.pdf'; //lien vers le pdf

        $chambre = $espace->getNomEspace();


        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          
            //Minimum et maxiumum de jours pour une réservation
            if($reservation->getDuree() < 2 ){
                $this->addFlash('error', 'La réservation doit être de deux nuits minimum');
                return $this->redirectToRoute('app_espace');
            } elseif($reservation->getDuree() > 28 ) {
                $this->addFlash('error', 'La réservation ne doit pas excéder 28 jours');
                return $this->redirectToRoute('app_espace');
            } elseif($reservation->getNbPersonnes() > $espace->getNbPlaces()) {
                //Vérifier que le nb de personnes dans la réservation == nbPlaces de la chambre
                $this->addFlash('error', 'Nombre de personnes trop élevé. Merci de réserver une autre chambre pour les personnes supplémentaires. (Hors enfants)');
                return $this->redirectToRoute('app_espace');
            } else {
                $reservation = $form->getData();
    
                // Calcul du prix total
                $prixTotal = $reservation->calculerPrixTotal();
                $reservation->setPrixTotal($prixTotal);
                
                // setEmail (il faut set les champs non nullables)
                $reservation->setEmail($email);
                $reservation->setEspace($espace);
                $reservation->setAdresseFacturation($adresseFacturation);
                $reservation->setFacture($facture);
    
                $entityManager->persist($reservation);
                $entityManager->flush();
         
                //Si non connecté redirection page de CHOIX (réserver en tant qu'invité, ou se connecter)
                // if(!$user){
                    // return $this->redirectToRoute('app_choix');
                // } else {
                    $this->addFlash('message', 'Les informations ont bien été prises en compte, vous allez passer à l\'étape suivante...');
                    return $this->redirectToRoute('new_coordonnees', ['reservation' => $reservation->getId()]);
                // }
            }
        }  
            return $this->render('reservation/new.html.twig', [
                'form' => $form,
                'chambre' => $chambre
            ]);
        }

        // #[Route('/reservation/choix', name:'app_choix') ]
        // public function choix()
        // {
        //     return $this->render('reservation/choix.html.twig');
        // }
}
