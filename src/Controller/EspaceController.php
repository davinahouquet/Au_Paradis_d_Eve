<?php

namespace App\Controller;

use App\Entity\Espace;
use App\Repository\EspaceRepository;
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
    public function show_espace(Espace $espace): Response
    {
        return $this->render('espace/show.html.twig', [
            'espace' => $espace
        ]);
    }
}
