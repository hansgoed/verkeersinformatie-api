<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Something that happens on the road.
 *
 * @ORM\Entity
 * @ORM\Table
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
abstract class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Road")
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
     * @ORM\Column(type="text")
     */
    protected string $description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    protected DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    protected DateTimeImmutable $resolvedAt;
}