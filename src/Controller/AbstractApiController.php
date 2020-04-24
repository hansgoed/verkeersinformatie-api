<?php

namespace App\Controller;

use App\Entity\Event\EventInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractApiController implements ApiControllerInterface
{
    private SerializerInterface $serializer;

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
}