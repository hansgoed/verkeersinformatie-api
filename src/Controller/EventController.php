<?php

namespace App\Controller;

use App\Repository\AbstractEventRepository;
use App\Repository\RoadworkRepository;
use App\Repository\TrafficJamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * API endpoints for traffic events.
 */
class EventController extends AbstractApiController
{
    /**
     * @Route("/traffic_jams")
     */
    public function showTrafficJams(TrafficJamRepository $trafficJamRepository, Request $request): JsonResponse
    {
        return $this->getEventResponse($trafficJamRepository, $request);
    }

    /**
     * @Route("/roadworks")
     */
    public function showRoadworks(RoadworkRepository $roadworkRepository, Request $request): JsonResponse
    {
        return $this->getEventResponse($roadworkRepository, $request);
    }

    /**
     * Collect the events from the repository & return a response that is completely ready to show the client.
     */
    private function getEventResponse(AbstractEventRepository $eventRepository, Request $request)
    {
        try {
            $dateTime = $this->getDateTimeFromRequest($request);
        }
        catch (\InvalidArgumentException $exception) {
            return new JsonResponse(
                ['message' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        $currentEvents = new ArrayCollection($eventRepository->getActiveEventsForDateTime($dateTime));

        $normalizedEvents = $this->normalizeEvents($currentEvents);

        return new JsonResponse($normalizedEvents);
    }
}