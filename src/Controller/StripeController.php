<?php
 
namespace App\Controller;
 
use Stripe;
use App\Entity\Reservation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
class StripeController extends AbstractController
{
    #[Route('/stripe/{id}', name: 'app_stripe')]
    public function index(Reservation $reservation): Response
    {
        $user = $reservation->getUser();

        $this->addFlash('success', 'La réservation a bien été prise en compte, veuillez trouver toutes les informations nécessaires dans votre boîte mail');
        if($user){
            return $this->redirectToRoute('reservations_a_venir');
        } else {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }
 
 
    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request, Reservation $reservation)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create ([
                "amount" => 5 * 100,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test"
        ]);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return $this->redirectToRoute('app_stripe', [], Response::HTTP_SEE_OTHER);
    }
}