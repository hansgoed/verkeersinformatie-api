<?php

namespace App\Anwb\Response\Events;

use App\Anwb\Response\Location;

/**
 * Description of an event on the road.
 */
abstract class AbstractEvent
{
    protected string $msgNr;
    protected string $from;
    protected Location $fromLoc;
    protected string $to;
    protected Location $toLoc;
    protected string $location;
    protected string $segStart;
    protected string $segEnd;
    protected string $reason;
    protected string $description;

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
        string $description
    ) {
        $this->msgNr = $msgNr;
        $this->from = $from;
        $this->fromLoc = $fromLoc;
        $this->to = $to;
        $this->toLoc = $toLoc;
        $this->location = $location;
        $this->segStart = $segStart;
        $this->segEnd = $segEnd;
        $this->reason = $reason;
        $this->description = $description;
    }
}