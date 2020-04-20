<?php

namespace App\Anwb\Response;

/**
 * A geographical location.
 */
class Location
{
    private string $lat;
    private string $lon;

    /**
     * Location constructor.
     */
    public function __construct(
        string $lat,
        string $lon
    ) {
        $this->lat = $lat;
        $this->lon = $lon;
    }
}