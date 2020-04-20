<?php

namespace App\Anwb\Response\Events;

use App\Anwb\Response\Location;
use DateTimeInterface;

/**
 * Details of roadworks.
 */
class RoadworksEvent extends AbstractEvent
{
    private DateTimeInterface $start;
    private DateTimeInterface $stop;

    public function __construct(
        string $msgNr,
        string $from,
        Location $fromLoc,
        string $to,
        Location $toLoc,
        string $location,
        string $segStart,
        string $segEnd,
        string $reason,
        string $description,
        DateTimeInterface $start,
        DateTimeInterface $stop
    ) {
        parent::__construct(
            $msgNr,
            $from,
            $fromLoc,
            $to,
            $toLoc,
            $location,
            $segStart,
            $segEnd,
            $reason,
            $description
        );

        $this->start = $start;
        $this->stop = $stop;
    }
}