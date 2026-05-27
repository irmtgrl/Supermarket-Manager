<?php

namespace App\Repository;

use App\Entity\InventoryItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InventoryItem>
 */
class InventoryItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventoryItem::class);
    }

    public function findExpired(): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.expirationDate < :today')
            ->setParameter('today', new \DateTimeImmutable())
            ->orderBy('i.expirationDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findExpiringSoon(int $extraDays = 7): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.expirationDate BETWEEN :today AND :future')
            ->setParameter('today', new \DateTimeImmutable())
            ->setParameter('future', new \DateTimeImmutable("+$extraDays days"))
            ->orderBy('i.expirationDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countExpiringSoon(int $extraDays = 7): int
    {
        return $this->createQueryBuilder('i')
            ->select('COUNT(i.id)')
            ->where('i.expirationDate BETWEEN :today AND :future')
            ->setParameter('today', new \DateTimeImmutable())
            ->setParameter('future', new \DateTimeImmutable("+$extraDays days"))
            ->getQuery()
            ->getSingleScalarResult();
    }
}
