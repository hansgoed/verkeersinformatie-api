<?php

namespace App\Repository;

use App\Entity\Event\AbstractEvent;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractEventRepository extends ServiceEntityRepository
{
    /**
     * @return AbstractEvent[]
     */
    public function getCurrentEvents(): array
    {
        return $this->getActiveEventsForDateTime(new DateTime());
    }

    /**
     * @return AbstractEvent[]
     */
    public function getActiveEventsForDateTime(DateTimeInterface $dateTime): array
    {
        $queryBuilder = $this->createQueryBuilder('event');
        $queryBuilder
            ->where(':datetime BETWEEN event.createdAt AND event.resolvedAt')
            ->orWhere(':datetime > event.createdAt AND event.resolvedAt IS NULL')
            ->setParameters(
                [
                    'datetime' => $dateTime,
                ]
            );

        return $queryBuilder->getQuery()->getResult();
    }
}