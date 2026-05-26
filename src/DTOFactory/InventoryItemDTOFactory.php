<?php

namespace App\DTOFactory;

use App\DTO\InventoryItemDTO;
use App\Entity\InventoryItem;
use DateTimeImmutable;

class InventoryItemDTOFactory
{
    public function create(InventoryItem $inventoryItem): InventoryItemDTO
    {
        $isExpired = $inventoryItem->getExpirationDate() < new DateTimeImmutable();
        $expiresSoon = $inventoryItem->getExpirationDate() < new DateTimeImmutable("+7 days");

        return new InventoryItemDTO(
            id: $inventoryItem->getId(),
            quantity: $inventoryItem->getQuantity(),
            expirationDate: $inventoryItem->getExpirationDate(),
            purchasePrice: $inventoryItem->getPurchasePrice(),
            isExpired: $isExpired,
            expiresSoon: $expiresSoon
        );
    }
}