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
    public function new_coordonnees(Reservation $reservation = null,  User $user = null, Espace $espace = null, EntityManagerInterface $entityManager, Request $request): Response
    {
        // $isNew = !$user;

        // if ($isNew) {
        //     $this->redirectToRoute('app_register');
        // }
        $user = $this->getUser();
        // Afficher toutes les infos de la chambre qu'on réserve
        $espace = $reservation->getEspace();
            
        $form = $this->createForm(CoordonneesType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            
            // dd($user);
            $password = $user->getPassword();
            $user->setPassword($password);
                // $user->setAdresse($adresse);
                // $user->setCp($cp);
                // $user->setVille($ville);
                // $user->setPays($pays);

            $entityManager->persist($user);
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
