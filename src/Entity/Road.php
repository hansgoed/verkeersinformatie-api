<?php

namespace App\Entity;

use App\Entity\Event\Roadwork;
use App\Entity\Event\TrafficJam;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Event\TrafficJam", mappedBy="road")
     *
     * @var TrafficJam[]|Collection
     */
    private Collection $trafficJams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event\Roadwork", mappedBy="road")
     *
     * @var Roadwork[]|Collection
     */
    private Collection $roadworks;

    /**
     * @var array TODO radars aren't saved yet.
     */
    private array $radars = [];

    /**
     * Road constructor.
     */
    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }

    /**
     * @Groups("road_context")
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @Groups("road_context")
     *
     * @return TrafficJam[]|Collection
     */
    public function getTrafficJams(): Collection
    {
        return $this->trafficJams;
    }

    /**
     * @Groups("road_context")
     *
     * @return Roadwork[]|Collection
     */
    public function getRoadworks(): Collection
    {
        return $this->roadworks;
    }

    /**
     * @Groups("road_context")
     *
     * @return array
     */
    public function getRadars(): array
    {
        return $this->radars;
    }
}