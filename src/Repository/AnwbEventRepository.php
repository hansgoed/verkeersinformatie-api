<?php

namespace App\Repository;

use App\Entity\AnwbEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AnwbEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnwbEvent::class);
    }

    public function persist(AnwbEvent $anwbEvent)
    {
        $this->getEntityManager()->persist($anwbEvent);
    }

    public function remove(AnwbEvent $anwbEventEntity)
    {
        $this->getEntityManager()->remove($anwbEventEntity);
    }
}