<?php

namespace App\Anwb\Response\Events;

use App\Anwb\Response\Location;
use DateTimeInterface;

/**
 * Details of roadworks.
 */
class RoadworkEvent extends AbstractEvent
{
    private DateTimeInterface $start;
    private DateTimeInterface $stop;

    /**
     * @param int|string $id
     */
    public function __construct(
        $id,
        string $from,
        Location $fromLoc,
        string $to,
        Location $toLoc,
        string $reason,
        DateTimeInterface $start,
        DateTimeInterface $stop
    ) {
        parent::__construct(
            $id,
            $from,
            $fromLoc,
            $to,
            $toLoc,
            $reason
        );

        $this->start = $start;
        $this->stop = $stop;
    }
}