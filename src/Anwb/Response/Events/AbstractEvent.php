<?php

namespace App\Anwb\Response\Events;

use App\Anwb\Response\Location;

/**
 * Description of an event on the road.
 */
abstract class AbstractEvent implements EventInterface
{
    /**
     * @var int|string
     */
    protected $id;

    protected string $from;
    protected Location $fromLoc;
    protected string $to;
    protected Location $toLoc;
    protected ?string $reason;

    /**
     * @param int|string $id
     */
    public function __construct(
        $id,
        string $from,
        Location $fromLoc,
        string $to,
        Location $toLoc,
        ?string $reason = null
    ) {
        $this->id = $id;
        $this->from = $from;
        $this->fromLoc = $fromLoc;
        $this->to = $to;
        $this->toLoc = $toLoc;
        $this->reason = $reason;
    }

    /**
     * @return int|string
     */
    public function getId()
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

    public function getReason(): ?string
    {
        return $this->reason;
    }
}