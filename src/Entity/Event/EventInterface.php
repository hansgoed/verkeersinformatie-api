<?php

namespace App\Entity\Event;

use App\Entity\Location;
use App\Entity\Road;
use DateTimeImmutable;

interface EventInterface
{
    public function getRoad(): Road;

    public function getStartLocation(): Location;

    public function getEndLocation(): Location;

    public function getDescription(): ?string;

    public function getCreatedAt(): DateTimeImmutable;

    public function getResolvedAt(): ?DateTimeImmutable;

    /**
     * Mark the event as resolved.
     */
    public function markResolved(): void;
}