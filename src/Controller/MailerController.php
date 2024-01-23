<?php

namespace App\Controller;

use App\Entity\Reservation;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmailApresSejour(MailerInterface $mailer, Reservation $reservation): Response
    {
            // Après la date de fin du séjour, email envoyé automatiquement avec le résumé du séjour
    
            // dateEnvoiMail = dateFin + 1
            // when dateEnvoieMail = currentDate
            // sendEmaiApresSejour

        // $emailReservation = $reservation->getEmail();
        // $prenom = $reservation->getPrenom();

        // $email = (new Email())
        //     ->from('admin@auparadisdeve.fr')
        //     ->to($emailReservation)
        //     ->subject('Au Paradis d\'Eve - Votre réservation!')
        //     ->text('Bonjour'. $prenom .'! vous avez réserver au sein de notre établissement, voici un rappel des règles etcetc + conditions d\'annulation + lien vers l\'annulation')
        //     ->html('template.html.twig');

        // $mailer->send($email);
    }
}