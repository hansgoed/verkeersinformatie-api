<?php

namespace App\Anwb\Response\Events;

use App\Anwb\Response\Location;

/**
 * Description of an event on the road.
 */
abstract class AbstractEvent implements EventInterface
{
    protected string $id;
    protected string $from;
    protected Location $fromLoc;
    protected string $to;
    protected Location $toLoc;
    protected string $reason;

    public function __construct(
        string $id,
        string $from,
        Location $fromLoc,
        string $to,
        Location $toLoc,
        string $reason
    ) {
        $this->id = $id;
        $this->from = $from;
        $this->fromLoc = $fromLoc;
        $this->to = $to;
        $this->toLoc = $toLoc;
        $this->reason = $reason;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getFromLoc(): Location
    {
        return $this->fromLoc;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getToLoc(): Location
    {
        return $this->toLoc;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}