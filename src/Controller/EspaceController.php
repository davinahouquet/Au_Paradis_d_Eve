<?php

namespace App\Controller;

use App\Entity\Espace;
use App\Form\EspaceType;
use App\Repository\EspaceRepository;
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

    #[Route('/espace/new', name: 'new_espace')]
    public function newEspace(Espace $espace, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$espace){
            $espace = new Espace();
        }

        $form = $this->createForm(EspaceType::class, $espace);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $espace = $form->getData();
            dd($espace);
            $entityManager->persist($espace);
            $entityManager->flush();

            $this->addFlash('success', 'Espace ajouté');

            return $this->redirectToRoute('app_espace');
        }

        return $this->render('espace/new.html.twig', [
            'form' => $form,
        ]);
    }
}
