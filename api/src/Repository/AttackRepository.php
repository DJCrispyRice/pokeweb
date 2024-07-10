<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Attack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class AttackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attack::class);
    }

    public function findAllWithLabelAsKey(): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a')
            ->from(Attack::class, 'a', 'a.label')
            ->getQuery()
            ->getResult();
    }
}
