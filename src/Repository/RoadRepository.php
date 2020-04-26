<?php

namespace App\Repository;

use App\Entity\Road;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class RoadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Road::class);
    }

    /**
     * Find all roads and join all current events.
     *
     * @return Road[]
     */
    public function findAllWithCurrentEvents(): array
    {
        $dateTime = new DateTime();

        return $this->findAllWithEventsFilteredByDate($dateTime);
    }

    /**
     * Find all roads with it's event relations filtered on actual at given datetime.
     *
     * @return Road[]
     */
    private function findAllWithEventsFilteredByDate(DateTime $dateTime): array
    {
        $queryBuilder = $this->createQueryBuilder('road');
        $this->joinEventFilteredByDate('trafficJams', 'traffic_jams', $queryBuilder, $dateTime);
        $this->joinEventFilteredByDate('roadworks', 'roadworks', $queryBuilder, $dateTime);
//        $this->addJoinJQueryBuilderForEvent('radars', 'radars', $queryBuilder, $formattedDatetime);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Join an event type filtered by date.
     */
    private function joinEventFilteredByDate(string $relationName, string $alias, QueryBuilder $queryBuilder, DateTimeInterface $dateTime)
    {
        $queryBuilder
            ->addSelect($alias)
            ->leftJoin('road.' . $relationName, $alias, Join::WITH,
                $this->getEventDateFilterExpression($queryBuilder, $alias, $dateTime)
            );
    }

    /**
     * Get the DQL expression to filter events on datetime.
     */
    private function getEventDateFilterExpression(QueryBuilder $queryBuilder, string $alias, DateTimeInterface $dateTime)
    {
        $queryBuilder->setParameter('datetime', $dateTime, Types::DATETIME_MUTABLE);

        return $queryBuilder->expr()->andX(
            $queryBuilder->expr()->lte($alias . '.createdAt', ':datetime'),
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->gt($alias . '.resolvedAt', ':datetime'),
                $queryBuilder->expr()->isNull($alias . '.resolvedAt')
            )
        );
    }
}