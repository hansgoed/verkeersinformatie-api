<?php

namespace App\Anwb\Response;

use App\Anwb\Response\Events\RadarEvent;
use App\Anwb\Response\Events\RoadworksEvent;
use App\Anwb\Response\Events\TrafficJamEvent;

/**
 * Object to get all different types of events from the response.
 */
class EventsCollection
{
    /**
     * @var TrafficJamEvent[]
     */
    private array $trafficJams;

    /**
     * @var RoadworksEvent[]
     */
    private array $roadWorks;

    /**
     * @var RadarEvent[]
     */
    private array $radars;

    /**
     * EventsCollection constructor.
     *
     * @param TrafficJamEvent[] $trafficJams
     * @param RoadworksEvent[] $roadWorks
     * @param RadarEvent[] $radars
     */
    public function __construct(
        array $trafficJams,
        array $roadWorks,
        array $radars
    ) {
        $this->trafficJams = $trafficJams;
        $this->roadWorks = $roadWorks;
        $this->radars = $radars;
    }
}