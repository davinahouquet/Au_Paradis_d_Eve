<?php

namespace App\Controller;

use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
