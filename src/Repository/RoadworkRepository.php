<?php

namespace App\Repository;

use App\Entity\Event\Roadwork;
use Doctrine\Persistence\ManagerRegistry;

class RoadworkRepository extends AbstractEventRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Roadwork::class);
    }
}