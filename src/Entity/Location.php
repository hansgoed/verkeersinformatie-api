<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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


}