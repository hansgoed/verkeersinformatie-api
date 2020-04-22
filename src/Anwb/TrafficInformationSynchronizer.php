<?php

namespace App\Anwb;

use App\Anwb\Response\Events\AbstractEvent;
use App\Anwb\Response\Events\EventInterface as EventResponseInterface;
use App\Entity\AnwbEvent;
use App\Entity\Event\EventInterface;
use App\Entity\Event\Roadwork;
use App\Entity\Event\TrafficJam;
use App\Entity\Location;
use App\Entity\Road;
use App\Repository\AnwbEventRepository;
use App\Repository\RoadRepository;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Fetch the traffic information from the ANWB and save it to the local database.
 */
class TrafficInformationSynchronizer
{
    private Client $anwbClient;
    private AnwbEventRepository $anwbEventRepository;
    private RoadRepository $roadRepository;
    private EntityManagerInterface $entityManager;

    private ?array $anwbEvents = null;

    public function __construct(
        Client $anwbClient,
        AnwbEventRepository $anwbEventRepository,
        RoadRepository $roadRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->anwbClient = $anwbClient;
        $this->anwbEventRepository = $anwbEventRepository;
        $this->roadRepository = $roadRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Fetch the traffic information from the ANWB and save them to the local database.
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function synchronize(): void
    {
        $currentTrafficInformation = $this->anwbClient->getTrafficInformation();

        $currentEvents = [];
        foreach ($currentTrafficInformation->getRoads() as $roadEntry) {
            $currentEvents = array_merge($currentEvents, $this->getFlatArrayWithEvents($roadEntry));
        }

        foreach ($currentEvents as $eventArray) {
            $eventEntity = $this->getEventEntityForAnwbEvent($eventArray['event']);
            if ($eventEntity !== null) {
                // This event is known. No need to register it.
                continue;
            }

            $this->registerNewAnwbEvent($eventArray['event'], $eventArray['type'], $eventArray['road']);
        }

        $this->markMissingEventsAsResolved($currentEvents);

        $this->entityManager->flush();
    }

    /**
     * Convert the RoadEntry to an array that will make it slightly easier to work with.
     *
     * @return array[]
     */
    private function getFlatArrayWithEvents(Response\Road $roadEntry): array
    {
        $road = $this->fetchOrCreateRoadEntity($roadEntry->getRoad());

        $events = [];
        $trafficJams = [];
        $roadworks = [];
        foreach ($roadEntry->getSegments() as $segment) {
            $trafficJams = array_merge($trafficJams, $segment->getJams());
            $roadworks = array_merge($roadworks, $segment->getRoadworks());
        }

        foreach ($trafficJams as $trafficJam) {
            $events[$trafficJam->getId()] = [
                'road' => $road,
                'event' => $trafficJam,
                'type' => AbstractEvent::EVENT_TYPE_TRAFFIC_JAM,
            ];
        }

        foreach ($roadworks as $roadwork) {
            $events[$roadwork->getId()] = [
                'road' => $road,
                'event' => $roadwork,
                'type' => AbstractEvent::EVENT_TYPE_ROADWORK,
            ];
        }

        return $events;
    }

    /**
     * Get the Road entity from the database or create a new one if it doesn't exist yet.
     *
     * @param string $roadName The name of the road, e.g. "A29" or "N59"
     */
    private function fetchOrCreateRoadEntity(string $roadName): Road
    {
        /** @var Road|null $road */
        $road = $this->roadRepository->findOneBy(['name' => $roadName]);
        if ($road !== null) {
            return $road;
        }

        return new Road(
            $roadName
        );
    }

    /**
     * Match a known event from the database with an event response, or return null if it's not known yet.
     */
    private function getEventEntityForAnwbEvent(EventResponseInterface $anwbEvent): ?EventInterface
    {
        $anwbEvents = $this->getAnwbEvents();

        foreach ($anwbEvents as $knownAnwbEvent) {
            if ($knownAnwbEvent->getReference() === $anwbEvent->getId()) {
                return $knownAnwbEvent->getEvent();
            }
        }

        return null;
    }

    /**
     * Get all known events from the database.
     *
     * @return AnwbEvent[]
     */
    private function getAnwbEvents(): array
    {
        if ($this->anwbEvents !== null) {
            return $this->anwbEvents;
        }

        $this->anwbEvents = $this->anwbEventRepository->findAll();

        return $this->anwbEvents;
    }

    /**
     * Add a new event to the database.
     */
    private function registerNewAnwbEvent(EventResponseInterface $event, string $type, Road $road)
    {
        $eventEntity = $this->createEventFromAnwbEvent(
            $event,
            $road,
            $type
        );

        $this->anwbEventRepository->persist(
            new AnwbEvent($event->getId(), $eventEntity)
        );
    }

    /**
     * Create a new event entity from an ANWB event response.
     */
    private function createEventFromAnwbEvent(EventResponseInterface $event, Road $road, string $type): EventInterface
    {
        $eventFQCN = $this->getEntityClassnameFromEventType($type);

        return new $eventFQCN(
            $road,
            new Location(
                $event->getFrom(),
                $event->getFromLoc()->getLat(),
                $event->getFromLoc()->getLon()
            ),
            new Location(
                $event->getTo(),
                $event->getToLoc()->getLat(),
                $event->getToLoc()->getLon()
            ),
            $event->getReason()
        );
    }

    /**
     * Get the FQCN for an entity for the given event type.
     *
     * @param string $type
     * @return string
     */
    private function getEntityClassnameFromEventType(string $type)
    {
        switch ($type) {
            case AbstractEvent::EVENT_TYPE_TRAFFIC_JAM:
                return TrafficJam::class;
            case AbstractEvent::EVENT_TYPE_ROADWORK:
                return Roadwork::class;
            default:
                throw new LogicException(
                    sprintf(
                        '"%s" is not a type that can be saved (yet).',
                        $type
                    )
                );
        }
    }

    /**
     * Compare known events with events in the response and mark those missing from the response as resolved.
     *
     * @param EventResponseInterface[] $currentEvents
     */
    private function markMissingEventsAsResolved(array $currentEvents)
    {
        $anwbEvents = $this->getAnwbEvents();
        foreach ($anwbEvents as $anwbEvent) {
            $reference = $anwbEvent->getReference();
            if (array_key_exists($reference, $currentEvents)) {
                continue;
            }

            $anwbEvent->getEvent()->markResolved();

            $this->anwbEventRepository->remove($anwbEvent);
        }
    }
}