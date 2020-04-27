<?php


namespace App\Repository;


use App\Entity\Event\EventInterface;
use DateTimeInterface;

interface EventRepositoryInterface
{
    /**
     * Get the events that had started but not finished yet at the time given.
     *
     * @return EventInterface[]
     */
    public function getActiveEventsForDateTime(DateTimeInterface $dateTime): array;
}