<?php


namespace App\Anwb\Response;


use App\Anwb\Response\Events\RadarEvent;
use App\Anwb\Response\Events\RoadworkEvent;
use App\Anwb\Response\Events\TrafficJamEvent;

class Segment
{
    private string $start;
    private string $end;
    /**
     * @var TrafficJamEvent[]
     */
    private array $jams;
    /**
     * @var RoadworkEvent[]
     */
    private array $roadworks;
    /**
     * @var RadarEvent[]
     */
    private array $radars;

    /**
     * Segment constructor.
     *
     * @param TrafficJamEvent[] $jams
     * @param RoadworkEvent[] $roadworks
     * @param RadarEvent[] $radars
     */
    public function __construct(
        string $start,
        string $end,
        array $jams = [],
        array $roadworks = [],
        array $radars = []
    ) {
        $this->start = $start;
        $this->end = $end;
        $this->jams = $jams;
        $this->roadworks = $roadworks;
        $this->radars = $radars;
    }

    public function getStart(): string
    {
        return $this->start;
    }

    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * @return TrafficJamEvent[]
     */
    public function getJams(): array
    {
        return $this->jams;
    }

    /**
     * @return RoadworkEvent[]
     */
    public function getRoadworks(): array
    {
        return $this->roadworks;
    }

    /**
     * @return RadarEvent[]
     */
    public function getRadars(): array
    {
        return $this->radars;
    }
}