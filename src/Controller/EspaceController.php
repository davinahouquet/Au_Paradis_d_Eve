<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Espace;
use App\Form\ImageType;
use App\Form\EspaceType;
use App\Repository\EspaceRepository;
use App\Repository\CategorieRepository;
use App\Services\ImageUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EspaceController extends AbstractController
{
    private ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService){
        $this->imageUploadService = $imageUploadService;
    }

    #[Route('/espace', name: 'app_espace')]
    public function index(EspaceRepository $espaceRepository): Response
    {
        $espaces = $espaceRepository->findAll();

        return $this->render('espace/index.html.twig', [
            'espaces' => $espaces
        ]);
    }

    #[Route('/espace/remove/{id}', name: 'remove_espace')]
    public function remove_espace(Espace $espace, EntityManagerInterface $entityManager, Image $image): Response
    {
        // si cet espace a une image associée, suppr l'image
        if($espace->getImages() !== null){
            $espace->removeImage($image);
        }

        $entityManager->remove($espace);
        $entityManager->flush();

        return $this->redirectToRoute('app_espace');
    }

    #[Route('/admin/espace/new', name: 'new_espace')]
    #[Route('/admin/espace/edit/{id}', name: 'edit_espace')] 
    public function new_edit_espace(Espace $espace = null, Image $image = null, CategorieRepository $categorieRepository, Request $request, EntityManagerInterface $entityManager, ImageUploadService $imageUploadService): Response
    {
        if(!$espace){
            $espace = new Espace();
        }
        $categories = $categorieRepository->findAll();

        $form = $this->createForm(EspaceType::class, $espace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $espace = $form->getData();
                $images = $form['imageFiles']->getData();
                $altImage = $form['altImage']->getData();
                $this->imageUploadService->uploadImages($images, $altImage, $espace);
                    
                $entityManager->persist($espace);
                $entityManager->flush();
    
                $this->addFlash('success', 'Espace ajouté');
    
                return $this->redirectToRoute('app_espace');
        }

        return $this->render('espace/new.html.twig', [
            'form' => $form,
            'categories' => $categories,
            'edit' =>$espace->getId()
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
