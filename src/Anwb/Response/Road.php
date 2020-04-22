<?php

namespace App\Anwb\Response;

/**
 * A road.
 */
class Road
{
    private string $road;
    private string $type;

    /**
     * @var Segment[]
     */
    private array $segments;

    /**
     * RoadEntry constructor.
     *
     * @param Segment[] $segments
     */
    public function __construct(
        string $road,
        string $type,
        array $segments
    ) {
        $this->road = $road;
        $this->type = $type;
        $this->segments = $segments;
    }

    public function getRoad(): string
    {
        return $this->road;
    }

    /**
     * @return Segment[]
     */
    public function getSegments(): array
    {
        return $this->segments;
    }


}