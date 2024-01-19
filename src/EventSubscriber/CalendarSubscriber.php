<?php

namespace App\EventSubscriber;
use CalendarBundle\Entity\Event;
use CalendarBundle\CalendarEvents;
use App\Repository\EspaceRepository;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CalendarSubscriber implements EventSubscriberInterface
{
    private RequestStack $requestStack;

    public function __construct(
        private EspaceRepository $espaceRepository,
        private UrlGeneratorInterface $router,
        RequestStack $requestStack
    )
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        
        $filters = $calendar->getFilters();
        // Récupérer la requête actuelle
        $request = $this->requestStack->getCurrentRequest();

        // Récupérer le chemin de l'URL référente
        $refererPathInfo = $request->headers->get('referer');

        // Utiliser Symfony pour extraire le paramètre 'id' du chemin de l'URL
        $segments = explode('/', trim($refererPathInfo, '/'));
        $espaceId = end($segments);

        $espace = $this->espaceRepository
            ->createQueryBuilder('e')
            ->where('e.id = :espaceId')
            ->setParameter('espaceId', $espaceId)
            ->getQuery()
            ->getSingleResult();
        ;
dd($_SESSION);
        foreach ($espace->getReservations() as $reservation) {
            $bookingEvent = new Event(
                $_SESSION['user']['role'] = 'ROLE_ADMIN' ? $reservation->getNom() . ' ' . $reservation->getPrenom()  : "Reservé",
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