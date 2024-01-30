<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use App\Entity\Reservation;
use App\Repository\UserRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfGeneratorController extends AbstractController
{
    #[Route('/profile/pdf/generator/{id}', name: 'app_pdf_generator')]
    public function index(Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $currentDate = date('l-d-m-Y');
        $reservation = $reservationRepository->findOneBy([]);
        $data = [
            'currentDate' => $currentDate,
            'reservation' => $reservation
        ];
        $html =  $this->renderView('pdf_generator/index.html.twig', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response(
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
}
