<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function searchByName(string $term): array
    {
        return $this->createQueryBuilder('p')
            ->where('LOWER(p.name) LIKE LOWER(:term)')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findLowStockProducts(int $treshold = 10): array
    {
        return $this->createQueryBuilder('p')
            ->select('p, SUM(i.quantity) AS totalStock')
            ->join('p.inventoryItems', 'i')
            ->groupBy('p.id')
            ->having('SUM(i.quantity) < :treshold')
            ->setParameter('treshold', $treshold)
            ->orderBy('totalStock', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findNewestProducts(): array 
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}


