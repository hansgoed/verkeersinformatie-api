<?php

namespace App\Controller;

use App\Entity\Road;
use App\Repository\RoadRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RoadController
{
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
                AbstractObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function (Road $object) {
                    return $object->getName();
                },
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
                AbstractObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function (Road $object) {
                    return $object->getName();
                },
            ]
        );

        return new JsonResponse($normalizedRoad);
    }
}