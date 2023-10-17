<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Espace;
use App\Entity\Reservation;
use App\Form\CoordonneesType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function new_coordonnees(Reservation $reservation,  User $user = null, Espace $espace = null, EntityManagerInterface $entityManager, Request $request): Response
    {
            // Afficher toutes les infos de la chambre qu'on réserve
            $espace = $reservation->getEspace();
                
            //Création du formulaire de coordonnées
            $form = $this->createForm(CoordonneesType::class);
            $form->handleRequest($request);
            
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
                
                //Définir l'user en session
                $user = $this->getUser();

                //si il y a bien un user connecté, et que la checkbox a été cochée
                if($user){
                    if($souvenir == true){
                        //Alors on set les informations du formulaire dans la table user
                        $user->setAdresse($adresse);
                        $user->setCp($cp);
                        $user->setVille($ville);
                        $user->setPays($pays);
    
                        $entityManager->persist($user);
                        $entityManager->flush();
                    }
                }
                //Et également dans la table réservation (via l'adresse de facturation)
                $entityManager->persist($reservation);
                $entityManager->flush();

                $this->addFlash('message', 'La réservation a bien été prise en compte');
                return $this->redirectToRoute('app_home');
            }
        return $this->render('reservation/coordonnees.html.twig', [
            'form' => $form,
            'espace' => $espace,
            'reservation' => $reservation
        ]);
    }
}
