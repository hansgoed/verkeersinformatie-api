<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A road.
 *
 * @ORM\Entity(repositoryClass="App\Repository\RoadRepository")
 * @ORM\Table
 */
class Road
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(unique=true)
     */
    private string $name;

    /**
     * Road constructor.
     */
    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }
}