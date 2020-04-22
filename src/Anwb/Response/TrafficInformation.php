<?php

namespace App\Anwb\Response;

/**
 * The top level of the response.
 */
class TrafficInformation
{
    /**
     * @var RoadEntry[]
     */
    private array $roadEntries;

    /**
     * TrafficInformation constructor.
     *
     * @param RoadEntry[] $roadEntries
     */
    public function __construct(array $roadEntries)
    {
        $this->roadEntries = $roadEntries;
    }

    /**
     * @return RoadEntry[]
     */
    public function getRoadEntries(): array
    {
        return $this->roadEntries;
    }
}