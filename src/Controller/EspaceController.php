<?php

namespace App\Controller;

use App\Entity\Espace;
use App\Form\EspaceType;
use App\Repository\EspaceRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function newEspace(Espace $espace, CategorieRepository $categorieRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$espace){
            $espace = new Espace();
        }
        $categories = $categorieRepository->findAll();

        $form = $this->createForm(EspaceType::class, $espace);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $espace = $form->getData();
            // dd($espace);
            $entityManager->persist($espace);
            $entityManager->flush();

            $this->addFlash('success', 'Espace ajouté');

            return $this->redirectToRoute('app_espace');
        }

        return $this->render('espace/new.html.twig', [
            'form' => $form,
            'categories' => $categories,
        ]);
    }
    
    #[Route('/espace/{id}', name: 'show_espace')]
    public function show_espace(Espace $espace, EspaceRepository $espaceRepository, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Espace::class);
        $chambre = $repository->findByCategorie(1);

        return $this->render('espace/show.html.twig', [
            'espace' => $espace,
            'chambre' => $chambre
        ]);
    }
}
