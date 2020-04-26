<?php

namespace App\Controller;

use App\Entity\Road;
use App\Repository\RoadRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RoadController extends AbstractApiController
{
    /**
     * @Route("/roads")
     *
     * @return JsonResponse
     */
    public function list(RoadRepository $roadRepository): JsonResponse
    {
        $roads = $roadRepository->findAllWithCurrentEvents();

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
}