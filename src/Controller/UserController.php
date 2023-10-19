<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Espace;
use App\Entity\Reservation;
use App\Form\CoordonneesType;
use App\Repository\UserRepository;
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
             // //la date du jour
            date_default_timezone_set('Europe/Paris');
            $currentDate = new \Datetime();
            // dd($currentDate);

            $facture ='lien.pdf'; //lien vers le pdf

             // Calcul du prix total
             $prixTotal = $reservation->calculerPrixTotal();

            // Afficher toutes les infos de la chambre qu'on réserve
            $espace = $reservation->getEspace();
                
            //Création du formulaire de coordonnées
            $form = $this->createForm(CoordonneesType::class);
            $form->handleRequest($request);
            
            // if($this->getUser()){
            //     //On pointe (get) les champs qu'on veut préremplir et on y insère (set) les valeurs souhaitées
            //     $form->get('email')->setData($this->getUser()->getEmail());
            //     $form->get('adresse')->setData($this->getUser()->getAdresse());
            //     $form->get('cp')->setData($this->getUser()->getCp());
            //     $form->get('ville')->setData($this->getUser()->getVille());
            //     $form->get('pays')->setData($this->getUser()->getPays());
            // }

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
                $reservation->setDateReservation($currentDate);
                $reservation->setPrixTotal($prixTotal);
                $reservation->setFacture($facture);

                
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

    #[Route('/user/{id}', name: 'app_user')]
    public function profil(User $user, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {

        $user = $userRepository->findOneBy([]);

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
