<?php

namespace App\Controller;

use App\Entity\Road;
use App\Repository\RoadRepository;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class RoadController extends AbstractApiController
{
    /**
     * @Route("/roads")
     *
     * @return JsonResponse
     */
    public function list(RoadRepository $roadRepository, Request $request): JsonResponse
    {
        try {
            $dateTime = $this->getDateTimeFromRequest($request);
        }
        catch (\InvalidArgumentException $exception) {
            return $this->createResponse(
                ['message' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        $roads = $roadRepository->findAllWithEvents($dateTime);

        $normalizedRoads = $this->serializer->normalize(
            $roads,
            null,
            [
                AbstractObjectNormalizer::GROUPS => [
                    self::SERIALIZATION_CONTEXT_ROAD,
                ],
            ]
        );

        return $this->createResponse($normalizedRoads);
    }

    /**
     * @Route("/roads/{name}")
     */
    public function show(Road $road, Request $request): JsonResponse
    {
        try {
            $dateTime = $this->getDateTimeFromRequest($request);
        }
        catch (\InvalidArgumentException $exception) {
            return $this->createResponse(
                ['message' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->applyDateTimeFilterToRoad($road, $dateTime);

        $normalizedRoad = $this->serializer->normalize(
            $road,
            null,
            [
                AbstractObjectNormalizer::GROUPS => [
                    self::SERIALIZATION_CONTEXT_ROAD,
                ],
            ]
        );

        return $this->createResponse($normalizedRoad);
    }

    /**
     * @Route("/roads/{name}/traffic_jams")
     */
    public function showTrafficJams(Road $road, Request $request)
    {
        try {
            $dateTime = $this->getDateTimeFromRequest($request);
        }
        catch (\InvalidArgumentException $exception) {
            return $this->createResponse(
                ['message' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->applyDateTimeFilterToRoad($road, $dateTime);

        $normalizedTrafficJams = $this->normalizeEvents($road->getTrafficJams());

        return $this->createResponse($normalizedTrafficJams);
    }

    /**
     * @Route("/roads/{name}/roadworks")
     */
    public function showRoadworks(Road $road, Request $request)
    {
        try {
            $dateTime = $this->getDateTimeFromRequest($request);
        }
        catch (\InvalidArgumentException $exception) {
            return $this->createResponse(
                ['message' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->applyDateTimeFilterToRoad($road, $dateTime);

        $normalizedRoadworks = $this->normalizeEvents($road->getRoadworks());

        return $this->createResponse($normalizedRoadworks);
    }

    /**
     * @Route("/roads/{name}/radars")
     */
    public function showRadars(Road $road, Request $request)
    {
        try {
            $dateTime = $this->getDateTimeFromRequest($request);
        }
        catch (\InvalidArgumentException $exception) {
            return $this->createResponse(
                ['message' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->applyDateTimeFilterToRoad($road, $dateTime);

        $normalizedRadars = $this->normalizeEvents($road->getRadars());

        return $this->createResponse($normalizedRadars);
    }

    private function applyDateTimeFilterToRoad(Road $road, DateTimeInterface $dateTime)
    {
        $road->setDateTimeFilter($dateTime);
    }
}