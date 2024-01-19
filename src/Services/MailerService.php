<?php

namespace App\Services;

use App\Entity\Reservation;
use Symfony\Component\Mailer\MailerInterface;
Use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct (private MailerInterface $mailer) {

    }

    public function sendEmail(
        $to = 'admin@auparadisdeve.fr',
        $subject = 'Sujet du mail',
        $content = '',
        $text = ''
    ): void{
        $email = (new Email())
            ->from('noreply@mysite.com')
            ->to($to)
            ->subject($subject)
            ->text($text)
            ->html($content);
        $this->mailer->send($email);
    }

    // Envoyer la facture PDF à la fin du séjour
    public function sendPDF(Reservation $reservation){
                
        $emailReservation = $reservation->getEmail();
        $email = (new Email())
        ->from('admin@auparadisdeve.fr')
        ->to($emailReservation)
        ->subject('Votre facture');

    }
}