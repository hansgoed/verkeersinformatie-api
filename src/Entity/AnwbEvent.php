<?php

namespace App\Entity;

use App\Entity\Event\EventInterface;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Every event that is currently active.
 *
 * @ORM\Entity(repositoryClass="App\Repository\AnwbEventRepository")
 * @ORM\Table
 */
class AnwbEvent
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
    private string $reference;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Event\AbstractEvent", cascade={"persist"})
     */
    private EventInterface $event;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(string $reference, EventInterface $event)
    {
        $this->reference = $reference;

        $this->createdAt = DateTimeImmutable::createFromFormat('U', time());
        $this->event = $event;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getEvent(): EventInterface
    {
        return $this->event;
    }
}