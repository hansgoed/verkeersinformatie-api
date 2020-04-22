<?php

namespace App\Anwb\Response\Events;

use App\Anwb\Response\Location;

interface EventInterface
{
    public const EVENT_TYPE_TRAFFIC_JAM = 'traffic_jam';
    public const EVENT_TYPE_ROADWORK = 'roadwork';
    public const EVENT_TYPE_RADAR = 'radar';

    public function getId(): string;

    public function getFrom(): string;

    public function getFromLoc(): Location;

    public function getTo(): string;

    public function getToLoc(): Location;

    public function getReason(): string;

}