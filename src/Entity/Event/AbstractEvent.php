<?php

namespace App\Entity\Event;

use App\Entity\Location;
use App\Entity\Road;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Something that happens on the road.
 *
 * @ORM\Entity(repositoryClass="AbstractEventRepository")
 * @ORM\Table(name="event")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
abstract class AbstractEvent implements EventInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Road", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected Road $road;

    /**
     * @ORM\Embedded(class="App\Entity\Location")
     */
    protected Location $startLocation;

    /**
     * @ORM\Embedded(class="App\Entity\Location")
     */
    protected Location $endLocation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    protected DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected ?DateTimeImmutable $resolvedAt;

    /**
     * AbstractEvent constructor.
     */
    public function __construct(
        Road $road,
        Location $startLocation,
        Location $endLocation,
        ?string $description = null,
        ?DateTimeImmutable $resolvedAt = null
    ) {
        $this->road = $road;
        $this->startLocation = $startLocation;
        $this->endLocation = $endLocation;
        $this->description = $description;
        $this->createdAt = DateTimeImmutable::createFromFormat('U', time());
        $this->resolvedAt = $resolvedAt;
    }

    /**
     * @Groups("road_context")
     * @Groups("event_context")
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getRoad(): Road
    {
        return $this->road;
    }

    /**
     * @Groups("event_context")
     * @Groups("road_context")
     *
     * @SerializedName("road")
     */
    public function getRoadName(): string
    {
        return $this->road->getName();
    }

    /**
     * @Groups("road_context")
     * @Groups("event_context")
     */
    public function getStartLocation(): Location
    {
        return $this->startLocation;
    }

    /**
     * @Groups("road_context")
     * @Groups("event_context")
     */
    public function getEndLocation(): Location
    {
        return $this->endLocation;
    }

    /**
     * @Groups("road_context")
     * @Groups("event_context")
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @Groups("road_context")
     * @Groups("event_context")
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @Groups("road_context")
     * @Groups("event_context")
     */
    public function getResolvedAt(): ?DateTimeImmutable
    {
        return $this->resolvedAt;
    }

    public function markResolved(): void
    {
        $this->resolvedAt = new DateTimeImmutable();
    }

    /**
     * Was the event current on the given DateTime?
     */
    public function isActual(DateTimeInterface $dateTime): bool
    {
        // Was it created?
        if ($this->getCreatedAt() > $dateTime) {
            return false;
        }

        // Is it still current?
        if ($this->getResolvedAt() === null) {
            return true;
        }

        // Is the entire event in the past?
        if ($this->getResolvedAt() < $dateTime) {
            return false;
        }

        return true;
    }
}