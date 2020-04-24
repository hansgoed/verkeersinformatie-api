<?php

namespace App\Controller;

use App\Entity\Event\EventInterface;
use App\Entity\Road;
use App\Repository\RoadRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RoadController
{
    const SERIALIZATION_CONTEXT_ROAD = 'road_context';
    const SERIALIZATION_CONTEXT_EVENT = 'event_context';

    /**
     * @var RoadRepository
     */
    private RoadRepository $roadRepository;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    public function __construct(
        RoadRepository $roadRepository,
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        $this->roadRepository = $roadRepository;
    }

    /**
     * @Route("/roads")
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $roads = $this->roadRepository->findAll();

        $normalizedRoads = $this->serializer->normalize(
            $roads,
            null,
            [
                AbstractObjectNormalizer::GROUPS => [
                    self::SERIALIZATION_CONTEXT_ROAD,
                ],
            ]
        );

        return new JsonResponse($normalizedRoads);
    }

    /**
     * @Route("/roads/{name}")
     */
    public function show(Road $road): JsonResponse
    {
        $normalizedRoad = $this->serializer->normalize(
            $road,
            null,
            [
                AbstractObjectNormalizer::GROUPS => [
                    self::SERIALIZATION_CONTEXT_ROAD,
                ],
            ]
        );

        return new JsonResponse($normalizedRoad);
    }

    /**
     * @Route("/roads/{name}/traffic_jams")
     */
    public function showTrafficJams(Road $road)
    {
        $normalizedTrafficJams = $this->normalizeEvents($road->getTrafficJams());

        return new JsonResponse($normalizedTrafficJams);
    }

    /**
     * @Route("/roads/{name}/roadworks")
     */
    public function showRoadworks(Road $road)
    {
        $normalizedRoadworks = $this->normalizeEvents($road->getRoadworks());

        return new JsonResponse($normalizedRoadworks);
    }

    /**
     * @Route("/roads/{name}/radars")
     */
    public function showRadars(Road $road)
    {
        $normalizedRadars = $this->normalizeEvents($road->getRadars());

        return new JsonResponse($normalizedRadars);
    }

    /**
     * @param EventInterface[]|Collection $events
     */
    private function normalizeEvents(Collection $events)
    {
        return $this->serializer->normalize(
            $events,
            null,
            [
                AbstractObjectNormalizer::GROUPS => [
                    self::SERIALIZATION_CONTEXT_EVENT,
                ],
            ]
        );
    }
}