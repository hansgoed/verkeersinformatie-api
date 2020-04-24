<?php

namespace App\Repository;

use App\Entity\Event\TrafficJam;
use Doctrine\Persistence\ManagerRegistry;

class TrafficJamRepository extends AbstractEventRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrafficJam::class);
    }
}