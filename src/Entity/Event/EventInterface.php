<?php

namespace App\Entity\Event;

use App\Entity\Location;
use App\Entity\Road;
use DateTimeImmutable;
use DateTimeInterface;

interface EventInterface
{
    public function getRoad(): Road;

    public function getStartLocation(): Location;

    public function getEndLocation(): Location;

    public function getDescription(): ?string;

    public function getCreatedAt(): DateTimeImmutable;

    public function getResolvedAt(): ?DateTimeImmutable;

    public function isActual(DateTimeInterface $dateTime): bool;

    /**
     * Mark the event as resolved.
     */
    public function markResolved(): void;
}