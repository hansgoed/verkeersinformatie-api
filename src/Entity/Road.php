<?php

namespace App\Entity;

use App\Entity\Event\EventInterface;
use App\Entity\Event\Roadwork;
use App\Entity\Event\TrafficJam;
use DateTimeInterface;
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

    private ?DateTimeInterface $dateFilter = null;

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
        if ($this->dateFilter === null) {
            return $this->trafficJams;
        }

        return $this->trafficJams->filter(function (EventInterface $event) {
            return $event->isActual($this->dateFilter);
        });
    }

    /**
     * @Groups("road_context")
     *
     * @return Roadwork[]|Collection
     */
    public function getRoadworks(): Collection
    {
        if ($this->dateFilter === null) {
            return $this->roadworks;
        }

        return $this->roadworks->filter(function (EventInterface $event) {
            return $event->isActual($this->dateFilter);
        });
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

    public function setDateTimeFilter(DateTimeInterface $dateTime)
    {
        $this->dateFilter = $dateTime;
    }
}