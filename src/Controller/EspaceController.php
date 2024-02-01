<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Espace;
use App\Form\ImageType;
use App\Form\EspaceType;
use App\Repository\EspaceRepository;
use App\Services\ImageUploadService;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EspaceController extends AbstractController
{
    private ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService){
        $this->imageUploadService = $imageUploadService;
    }

    // Onglet Chambres et Espaces de vie
    #[Route('/espace', name: 'app_espace')]
    public function index(EspaceRepository $espaceRepository): Response
    {
        $espaces = $espaceRepository->findEspacesNonLouables();
        $chambres = $espaceRepository->findEspacesLouables();

        return $this->render('espace/index.html.twig', [
            'espaces' => $espaces,
            'chambres' => $chambres
        ]);
    }

    #[Route('/espace/remove/{id}', name: 'remove_espace')]
    public function remove_espace(Espace $espace, EntityManagerInterface $entityManager): Response
    {

        $reservations = $espace->getReservations();

        if(sizeof($reservations) > 1){
            $this->addFlash('error', 'Cet espace est réservé, vous ne pouvez pas le supprimer si des réservations y sont associés.');
            return $this->redirectToRoute('show_espace', ['id' => $espace->getId()]);
        }

        $entityManager->remove($espace);
        $entityManager->flush();

        $this->addFlash('success', 'Espace supprimé');
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
                
                if($altImage == null){
                    $altImage = 'Texte de remplacement indisponible';
                }

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
        $espaces = $espaceRepository->findAll();
        // Filtre les espaces pour exclure celui qu'on affiche
        $espaces = array_filter($espaces, function ($e) use ($espace) {
            return $e->getId() !== $espace->getId();
        });
        shuffle($espaces); //Permet d'afficher aléatoirement les autres epsaces pour donner plus de visibilité

        $reservations = $espace->getReservations();
        $reservationData = [];

        foreach ($reservations as $reservation) {
            $reservationData[] = [
                'title' => 'Indisponible',
                'start' => $reservation->getDateDebut()->format('Y-m-d'),
                'end' => $reservation->getDateFin()->format('Y-m-d'),
            ];
        }

        $repository = $entityManager->getRepository(Espace::class);
        $chambre = $repository->findByCategorie(1);
        $reservations = $espace->getReservations();
        return $this->render('espace/show.html.twig', [
            'espace' => $espace,
            'chambre' => $chambre,
            'reservations' => $reservations,
            'espaces' => $espaces,
            'reservationData' => json_encode($reservationData),
        ]);
    }
}