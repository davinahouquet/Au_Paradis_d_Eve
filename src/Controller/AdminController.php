<?php

namespace App\Controller;

use App\Form\HomeTextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/edit_home_text', name: 'admin_edit_home_text')]
    public function editHomeText(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jsonFilePath = '../public/json/home_text.json';
        $jsonData = file_get_contents($jsonFilePath);
        $data = json_decode($jsonData, true);

        $form = $this->createForm(HomeTextType::class);

            $form->get('maison_dhote')->setData($data['maison_dhote']);
            $form->get('reservation')->setData($data['reservation']);
            $form->get('environnement')->setData($data['environnement']);
            $form->get('pays_de_bitche')->setData($data['pays_de_bitche']);
            $form->get('description_pays_de_bitche')->setData($data['description_pays_de_bitche']);
            $form->get('mascotte')->setData($data['mascotte']);
            $form->get('description_mascotte')->setData($data['description_mascotte']);
            $form->get('telephone_fixe')->setData($data['telephone_fixe']);
            $form->get('telephone')->setData($data['telephone']);
            $form->get('email')->setData($data['email']);
            $form->get('adresse')->setData($data['adresse']);
            $form->get('cp')->setData($data['cp']);
            $form->get('ville')->setData($data['ville']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->get($data);
            $updatedData = $form->getData();
            $updatedJson = json_encode($updatedData, JSON_PRETTY_PRINT);

            file_put_contents($jsonFilePath, $updatedJson);

            $this->addFlash('success', 'Les informations ont été mises à jour avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/edit_home_text.html.twig', [
            'form' => $form,
        ]);
    }
}
