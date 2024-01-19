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
    public function sendEmail(MailerInterface $mailer, Reservation $reservation): Response
    {
        $emailReservation = $reservation->getEmail();
        $prenom = $reservation->getPrenom();

        $email = (new Email())
            ->from('admin@auparadisdeve.fr')
            ->to($emailReservation)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Au Paradis d\'Eve - Votre réservation!')
            ->text('Bonjour'. $prenom .'! vous avez réserver au sein de notre établissement, voici un rappel des règles etcetc + conditions d\'annulation + lien vers l\'annulation')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        // ...
    }
}