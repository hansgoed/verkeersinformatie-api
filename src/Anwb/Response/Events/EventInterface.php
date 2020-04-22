<?php

namespace App\Anwb\Response\Events;

use App\Anwb\Response\Location;

interface EventInterface
{
    public const EVENT_TYPE_TRAFFIC_JAM = 'traffic_jam';
    public const EVENT_TYPE_ROADWORK = 'roadwork';
    public const EVENT_TYPE_RADAR = 'radar';

    public function getMsgNr(): string;

    public function getFrom(): string;

    public function getFromLoc(): Location;

    public function getTo(): string;

    public function getToLoc(): Location;

    public function getLocation(): string;

    public function getSegStart(): string;

    public function getSegEnd(): string;

    public function getReason(): string;

    public function getDescription(): string;

}