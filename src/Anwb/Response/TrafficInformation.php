<?php

namespace App\Anwb\Response;

/**
 * The top level of the response.
 */
class TrafficInformation
{
    /**
     * @var Road[]
     */
    private array $roads;

    /**
     * TrafficInformation constructor.
     *
     * @param Road[] $roads
     */
    public function __construct(array $roads)
    {
        $this->roads = $roads;
    }

    /**
     * @return Road[]
     */
    public function getRoads(): array
    {
        return $this->roads;
    }
}