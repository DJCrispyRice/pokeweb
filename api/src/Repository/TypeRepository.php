<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Type::class);
    }

    public function findAllWithLabelAsKey(): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('t')
            ->from(Type::class, 't', 't.label')
            ->getQuery()
            ->getResult();
    }
}
