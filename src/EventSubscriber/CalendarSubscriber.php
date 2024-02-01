<?php
namespace App\EventSubscriber;
use CalendarBundle\Entity\Event;
use CalendarBundle\CalendarEvents;
use App\Repository\EspaceRepository;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class CalendarSubscriber implements EventSubscriberInterface
{
        private $requestStack;
        private $authorizationChecker;


    public function __construct(
        private EspaceRepository $espaceRepository,
        private UrlGeneratorInterface $router,
        RequestStack $requestStack,
        AuthorizationCheckerInterface $authorizationChecker
    )
    {
        $this->requestStack = $requestStack;
        $this->authorizationChecker = $authorizationChecker;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    // Pour mettre des données dans le calendrier.html.twig
    public function onCalendarSetData(CalendarEvent $calendar)
    {
        // Récupérer l'espaceId
        $espaceId = $this->requestStack->getCurrentRequest()->get('espaceId');

        $espace = $this->espaceRepository->find($espaceId);

        if(isset($espace)) {
            foreach ($espace->getReservations() as $reservation) {
                $bookingEvent = new Event(
                    $this->authorizationChecker->isGranted('ROLE_ADMIN') ? $reservation->getNom() . ' ' . $reservation->getPrenom()  : "Reservé",
                    $reservation->getDateDebut(),
                    $reservation->getDateFin()
                );

                $bookingEvent->setOptions([
                    'backgroundColor' => 'red',
                    'borderColor' => 'red',
                ]);
                $bookingEvent->addOption(
                    'url',
                    $this->router->generate('app_booking_show', [
                        'id' => $reservation->getId(),
                    ])
                );

                $calendar->addEvent($bookingEvent);
            }
        }
    }
}