<?php

namespace App\Controller;

use App\Entity\Event\EventInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractApiController implements ApiControllerInterface
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param EventInterface[]|Collection $events
     */
    protected function normalizeEvents(Collection $events)
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

    /**
     * Get the datetime parameter from the request & return it as a proper object.
     *
     * @throws InvalidArgumentException when the datetime parameter could not be parsed
     */
    protected function getDateTimeFromRequest(Request $request): DateTimeInterface
    {
        $dateTimeParameter = $request->query->get('datetime');
        if ($dateTimeParameter === null) {
            return new DateTime();
        }

        $dateTime = \DateTimeImmutable::createFromFormat(DATE_RFC3339_EXTENDED, $dateTimeParameter);

        if ($dateTime === false) {
            throw new InvalidArgumentException('Datetime parameter ("' . $dateTimeParameter . '") is not in the RFC3339 format');
        }

        return $dateTime;
    }

    /**
     * Create an API response that is accessible from everywhere.
     */
    protected function createResponse(array $normalizedData, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse($normalizedData, $statusCode, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }
}