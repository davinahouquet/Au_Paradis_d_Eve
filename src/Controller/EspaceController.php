<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Espace;
use App\Form\ImageType;
use App\Form\EspaceType;
use App\Repository\EspaceRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EspaceController extends AbstractController
{
    #[Route('/espace', name: 'app_espace')]
    public function index(EspaceRepository $espaceRepository): Response
    {
        $espaces = $espaceRepository->findAll();

        return $this->render('espace/index.html.twig', [
            'espaces' => $espaces
        ]);
    }

    #[Route('/espace/remove/{id}', name: 'remove_espace')]
    public function remove_espace(Espace $espace, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($espace);
        $entityManager->flush();

        return $this->redirectToRoute('app_espace');
    }

    #[Route('/espace/new', name: 'new_espace')]
    #[Route('/espace/edit/{id}', name: 'edit_espace')] 
    public function new_edit_espace(Espace $espace = null, Image $image = null, CategorieRepository $categorieRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // dd($espace->getImages());
        if(!$espace){
            $espace = new Espace();
        }
        $categories = $categorieRepository->findAll();

        $form = $this->createForm(EspaceType::class, $espace);
        // $formImage = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);
        // $formImage->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // dump();
            // if($formImage->isSubmitted() && $formImage->isValid()) {
                // $images = $form['images']->getData(); 
                // dd($images);
                $espace = $form->getData();

                $images = $form->get('images')->getData();

                foreach ($images as $imageFile) {
                    // Create a new Image entity
                    $image = new Image();

                    // Generate a unique filename and set it as the link
                    $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                    $imageFile->move(
                        $this->getParameter('../public/img/espaces/'), // Set your images directory parameter
                        $fileName
                    );

                    // Set the link and alt attributes
                    $image->setLienImage($fileName);
                    $image->setAltImage('Alt description'); // You can customize this as needed

                    // Associate the image with the Espace entity
                    $image->setEspace($espace);
                
                // dd($form->getData());
                // dd($espace);
                // $espace->addImage();

                $entityManager->persist($espace);
                $entityManager->flush();
    
                $this->addFlash('success', 'Espace ajouté');
    
                return $this->redirectToRoute('app_espace');
                }
            // }
            // dump();
        }

        return $this->render('espace/new.html.twig', [
            'form' => $form,
            // 'formImage' => $formImage,
            'categories' => $categories,
        ]);
    }
    
    #[Route('/espace/{id}', name: 'show_espace')]
    public function show_espace(Espace $espace, EspaceRepository $espaceRepository, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Espace::class);
        $chambre = $repository->findByCategorie(1);
        // $notes = $espaceRepository->getReservations()->getNote();
        // $avis = $espaceRepository->getReservations()->getAvis();
        $reservations = $espace->getReservations();
        // $notes = $reservations->getNote();
        // $evaluations = $notes + $avis;

        return $this->render('espace/show.html.twig', [
            'espace' => $espace,
            'chambre' => $chambre,
            // 'notes' => $notes,
            'reservations' => $reservations
            // 'evaluations' => $evaluations
        ]);
    }
}
