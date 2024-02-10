<?php

namespace App\Controller;

use App\Entity\Option;
use App\Form\OptionType;
use App\Form\SortieType;
use App\Form\HomeTextType;
use App\Entity\Reservation;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Mime\Email;
use App\Form\QuestionsPratiquesType;
use App\Repository\OptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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

    // Calendrier de l'administrateur
    #[Route('/admin/calendrier', name: 'calendrier_admin')]
    public function calendrierAdmin(): Response
    {
        

        return $this->render('booking/index.html.twig', [
            'calendrier' => 'calendrier',
        ]);
    }

    // [DEPRECATED]
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

    //Afficher la liste des options
    #[Route('/admin/options', name: 'liste_options')]
    public function options(OptionRepository $optionRepository): Response
    {
        $options = $optionRepository->findAll();
    
        return $this->render('admin/liste_options.html.twig', [
            'options' => $options
        ]);
    }

    //Ajouter ou modifier une option
    #[Route('/admin/edit/option/{id}', name: 'edit_option')]
    #[Route('/admin/new/option', name: 'new_option')] 
    public function NewEditOption(Option $option, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$option){
            $option = new Option();
        }

        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $option = $form->getData();

                $entityManager->persist($option);
                $entityManager->flush();
    
                $this->addFlash('success', 'Options modifiées');
                return $this->redirectToRoute('liste_options');
        }
    
        return $this->render('admin/edit_option.html.twig', [
            'form' => $form
        ]);
    }

    //Supprimer une option
    #[Route('/admin/remove/option/{id}', name: 'remove_option')]
    public function removeOption(Option $option, Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservations = $option->getReservations();

        if(sizeof($reservations) > 1){
            $this->addFlash('error', 'Ces options sont inclues dans des réservations. Vous ne pouvez pas la supprimer si des réservations y sont associées.');
            return $this->redirectToRoute('liste_options');
        }

        $entityManager->remove($option);
        $entityManager->flush();

        $this->addFlash('success', 'Option supprimée');
        return $this->redirectToRoute('liste_options');
    }
    

    // Edition des textes de la page d'accueil
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
            // $form->get($data);
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

    // Edition des textes des alentours
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

    // Vue sur l'ensemble des réservations
    #[Route('/admin/toutes/reservations', name: 'toutes_reservations')]
    public function voirToutesReservations()
    {
        return $this->render('admin/reservations.html.twig');
    }    

    // Edition des questions pratiques
    #[Route('/admin/edit/question/{id}', name: 'edit_question')]
    public function editQuestion(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $jsonFilePath = '../public/json/questions_pratiques.json';
        $jsonData = file_get_contents($jsonFilePath);
        $data = json_decode($jsonData, true);
    
        $questionToEdit = null;
        foreach ($data as &$question) { //Le symbole & dans la boucle foreach crée une référence à chaque élément du tableau plutôt que de créer une copie de la valeur. Sans le &, la boucle foreach travaillerait sur des copies des éléments du tableau, et toute modification apportée à ces copies n'aurait aucun impact sur le tableau d'origine.
            if ($question['id'] == $id) {
                $questionToEdit = $question;
                break;
            }
        }
    
        $form = $this->createForm(QuestionsPratiquesType::class, $questionToEdit);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $updatedData = $form->getData();
    
            foreach ($data as &$question) {
                if ($question['id'] == $id) {
                    $question = $updatedData;
                    break;
                }
            }
    
            $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($jsonFilePath, $updatedJson);
    
            $this->addFlash('success', 'Les informations ont été mises à jour avec succès.');
            return $this->redirectToRoute('questions_pratiques');
        }
    
        return $this->render('admin/edit_questions.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    //Supprimer un avis
    #[Route('/admin/remove/avis/{id}', name: 'remove_avis')]
    public function removeAvis(Reservation $reservation, Request $request, EntityManagerInterface $entityManager): Response
    {

         // Modifier l'avis de la réservation pour le remplacer par "Avis supprimé"
        $reservation->setAvis("Avis supprimé");
    
        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        $this->addFlash('success', 'Avis supprimé');
        return $this->redirectToRoute('avis');
    }

    //Envoyer justificatif après fin du séjour
    #[Route('/admin/envoyer/justificatif/{id}', name: 'envoyer_justificatif')]
    public function envoiJustificatif(Reservation $reservation, Request $request, EntityManagerInterface $entityManager,  MailerInterface $mailer): Response
    {
        // Vérifier que la date de fin de séjour est bien dépassée

        // Récupérer l'adresse e-mail du client
        $destinataireEmail = $reservation->getEmail();
        $espace = $reservation->getEspace();
        $currentDate = date('l-d-m-Y');
        // Générer le PDF
        // $pdfContent = $this->generatePdfContent($reservation);

        // Créer un nouvel e-mail
        $email = (new Email())
            ->from('votre_adresse_email@example.com')
            ->to($destinataireEmail)
            ->subject('Au Paradis d\'Eve - Justificatif de réservation')
            ->html($this->renderView('mailer/justificatif.html.twig', [
                'reservation' => $reservation,
                'espace' => $espace,
                'currentDate' => $currentDate,
            ]));

        // Envoyer l'e-mail
        $mailer->send($email);

        $this->addFlash('success', 'Justificatif PDF envoyé');
        return $this->redirectToRoute('reservations_passees');
    }
}