<?php

namespace App\Controller;

use App\Entity\Espace;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageController extends AbstractController
{
    #[Route('/image', name: 'app_image')]
    public function index(): Response
    {
        return $this->render('image/index.html.twig', [
            'controller_name' => 'ImageController',
        ]);
    }

    // #[Route('/new/image/{id}', name: 'new_image')]
    // public function newImage(Espace $espace, Request $request, EntityManagerInterface $entityManager): Response
    // {

    //     $form = $this->createForm(ImageType::class);

    //     $form->handleRequest($request);

    //     if($form->isSubmitted() && $form->isValid()){

    //         $image = $form->getData();

    //         $entityManager->persist($image);
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Image ajoutée');

    //         return $this->redirectToRoute('app_espace', ['id' => $espace->getId()]);
    //     }


    //     return $this->render('image/new.html.twig', [
    //         'form' => $form
    //     ]);
    // }
}
