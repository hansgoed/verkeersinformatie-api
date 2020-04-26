<?php

namespace App\Controller;

use App\Entity\Road;
use App\Repository\RoadRepository;
use DateTime;
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
            return new JsonResponse(
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

        return new JsonResponse($normalizedRoads);
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
            return new JsonResponse(
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

    private function applyDateTimeFilterToRoad(Road $road, DateTimeInterface $dateTime)
    {
        $road->setDateTimeFilter($dateTime);
    }

    private function getDateTimeFromRequest(Request $request): DateTimeInterface
    {
        $dateTimeParameter = $request->query->get('datetime');
        if ($dateTimeParameter === null) {
            return new DateTime();
        }

        $dateTime = \DateTimeImmutable::createFromFormat(DATE_RFC3339, $dateTimeParameter);

        if ($dateTime === false) {
            throw new \InvalidArgumentException('Datetime parameter ("' . $dateTimeParameter . '") is not in the RFC3339 format');
        }

        return $dateTime;
    }
}