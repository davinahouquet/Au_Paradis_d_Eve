<?php

namespace App\Controller;

use App\Entity\Espace;
use App\Form\ContactType;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, Request $request, MailerService $mailer): Response
    {
        // Récupère le contenu du fichier JSON
        $jsonData = file_get_contents('../public/json/home_text.json');
        // Convertir JSON en tableau associatif
        $data = json_decode($jsonData, true);
        $repository = $entityManager->getRepository(Espace::class);
        // Va trier les espaces selon la categorie 1 (Qui correspond aux chambres dans ma BDD)
        $chambres = $repository->findByCategorie(1);

        //Formulaire de contact
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
            $subject = 'Demande de contact sur votre site de ' . $contactFormData['email'];
            $content = $contactFormData['message'];
            $mailer->sendEmail(subject: $subject, content: $content);

            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('home/index.html.twig', [
            'chambres' => $chambres,
            'form' => $form->createView(),
            'data' => $data
        ]);
    }

    #[Route('/mentions', name: 'mentions')]
    public function mentions(){

        return $this->render('home/mentions_legales.html.twig');
    }

    #[Route('/conditions', name: 'conditions')]
    public function conditions(){

        return $this->render('home/conditions_generales.html.twig');
    }
}
