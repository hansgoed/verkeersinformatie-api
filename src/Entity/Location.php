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
}