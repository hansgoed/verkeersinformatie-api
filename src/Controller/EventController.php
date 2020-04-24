<?php

namespace App\Controller;

use App\Repository\AbstractEventRepository;
use App\Repository\RoadworkRepository;
use App\Repository\TrafficJamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * API endpoints for traffic events.
 */
class EventController extends AbstractApiController
{
    /**
     * @Route("/traffic_jams")
     */
    public function showTrafficJams(TrafficJamRepository $trafficJamRepository): JsonResponse
    {
        return $this->getEventResponse($trafficJamRepository);
    }

    /**
     * Collect the events from the repository & return a response that is completely ready to show the client.
     */
    private function getEventResponse(AbstractEventRepository $eventRepository)
    {
        $currentEvents = new ArrayCollection($eventRepository->getCurrentEvents());

        $normalizedEvents = $this->normalizeEvents($currentEvents);

        return new JsonResponse($normalizedEvents);
    }

    /**
     * @Route("/roadworks")
     */
    public function showRoadworks(RoadworkRepository $roadworkRepository): JsonResponse
    {
        return $this->getEventResponse($roadworkRepository);
    }
}