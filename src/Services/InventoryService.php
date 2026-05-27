<?php 

namespace App\Services;

class InventoryService
{
    public function __construct(

    ){}

    public function findAvailableInventoryForProduct(Product $product): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.product = :product')
            ->andWhere('i.quantity > 0')
            ->andWhere('i.expirationDate > :today')
            ->setParameter('product', $product)
            ->setParameter('today', new \DateTimeImmutable())
            ->orderBy('i.expitaiondate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function reduceStock(
        Product $product,
        int $requestedQuantity
    ): void
    { 
        $inventoryItems = $this
            ->inventoryItemsRepository
            ->findAvailableInventoryForProduct($product);

        $remainingQuantity = $requestedQuantity;

        foreach($inventoryItems as $inventoryItem) {
            if($remainingQuantity < 0) {
                break;
            }

            $availableQuantity = $inventoryItem->getQuantity();

            if($availableQuantity >= $remainingQuantity) {
                $inventoryItem->setQuantity(
                    $availableQuantity - $remainingQuantity
                );
                $remainingQuantity = 0; 
            } else {
                $inventoryItem->setQuantity(0);
                $remainingQuantity -= $availableQuantity;
            }
        }

        if($remainingQuantity > 0) {
            throw new \Exception(
                'Not enough stock available'
            );
        }

        $this->entityManager->flush();
    }
}