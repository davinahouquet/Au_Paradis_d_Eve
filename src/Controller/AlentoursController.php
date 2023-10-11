<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlentoursController extends AbstractController
{
    #[Route('/alentours', name: 'app_alentours')]
    public function index(): Response
    {
        return $this->render('alentours/index.html.twig', [
            'controller_name' => 'AlentoursController',
        ]);
    }
}
