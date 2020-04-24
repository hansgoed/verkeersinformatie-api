<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * A location in the world.
 *
 * @ORM\Embeddable
 */
class Location
{
    /**
     * @ORM\Column
     */
    private string $name;

    /**
     * @ORM\Column
     */
    private string $latitude;

    /**
     * @ORM\Column
     */
    private string $longitude;

    /**
     * Location constructor.
     * @param string $name
     * @param string $latitude
     * @param string $longitude
     */
    public function __construct(string $name, string $latitude, string $longitude)
    {
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @Groups("road_context")
     * @Groups("event_context")
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @Groups("road_context")
     * @Groups("event_context")
     */
    public function getLatitude(): string
    {
        return $this->latitude;
    }

    /**
     * @Groups("road_context")
     * @Groups("event_context")
     */
    public function getLongitude(): string
    {
        return $this->longitude;
    }
}