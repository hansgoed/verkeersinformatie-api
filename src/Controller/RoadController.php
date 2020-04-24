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
}