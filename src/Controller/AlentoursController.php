<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AlentoursController extends AbstractController
{
    #[Route('/alentours', name: 'app_alentours')]
    public function index(): Response
    {
        $jsonData = file_get_contents('../public/json/alentours.json');
        $data = json_decode($jsonData, true);

        return $this->render('alentours/index.html.twig', [
            'data' => $data
        ]);
    }
}
