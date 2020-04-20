<?php

namespace App\Anwb\Response;

/**
 * A road.
 */
class RoadEntry
{
    private string $road;
    private string $roadType;

    /**
     * @var EventsCollection
     */
    private EventsCollection $events;

    /**
     * RoadEntry constructor.
     */
    public function __construct(
        string $road,
        string $roadType,
        EventsCollection $events
    ) {
        $this->road = $road;
        $this->roadType = $roadType;
        $this->events = $events;
    }
}