<?php

namespace App\Anwb\Response;

/**
 * A geographical location.
 */
class Location
{
    private float $lat;
    private float $lon;

    /**
     * Location constructor.
     */
    public function __construct(
        float $lat,
        float $lon
    ) {
        $this->lat = $lat;
        $this->lon = $lon;
    }
}