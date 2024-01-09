<?php

namespace App\Controller;

use App\Form\SortieType;
use App\Form\HomeTextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    public function listeOptions(): Response
    {
        $jsonFilePath = '../public/json/options.json';
        $jsonData = file_get_contents($jsonFilePath);
        $listeOptions = json_decode($jsonData, true);

        // Les options
        $optionsFile = file_get_contents('../public/json/options.json');
        $optionsData = json_decode($optionsFile, true);
        $listeOptions = [];
        foreach ($optionsData['options_chambres_hotes'] as $optionData) {
            $options[$optionData['nom_option']] = $optionData['id_option'];
        }

        return $this->render('user/index.html.twig', [
            'listeOptions' => $listeOptions,
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

        
    #[Route('/admin/edit/sortie/{id}', name: 'edit_alentours')]
    public function editSortie(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $jsonFilePath = '../public/json/alentours.json';
        $jsonData = file_get_contents($jsonFilePath);
        $data = json_decode($jsonData, true);
    
        $sortieToEdit = null;
        foreach ($data['sorties'] as $sortie) {
            if ($sortie['id_sortie'] == $id) {
                $sortieToEdit = $sortie;
                break;
            }
        }
        $form = $this->createForm(SortieType::class, $sortieToEdit);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $updatedData = $form->getData();
    
            foreach ($data['sorties'] as &$sortie) {
                if ($sortie['id_sortie'] == $id) {
                    $sortie = $updatedData;
                    break;
                }
            }
            $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($jsonFilePath, $updatedJson);
    
            $this->addFlash('success', 'Les informations ont été mises à jour avec succès.');
            return $this->redirectToRoute('app_alentours');
        }
        return $this->render('admin/edit_sorties.html.twig', [
            'form' => $form->createView(),
        ]);
    }    

    #[Route('/admin/toutes/reservations', name: 'toutes_reservations')]
    public function voirToutesReservations()
    {
        return $this->render('admin/reservations.html.twig');
    }   
}
