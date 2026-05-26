<?php

namespace App\DTOFactory;

use App\DTO\ProductDTO;
use App\Entity\Product;

class ProductDTOFactory
{
    public function create(Product $product): ProductDTO
    {
        $totalStock = 0;

        foreach($product->getInventoryItems() as $inventoryItem) 
        {
            $totalStock += $inventoryItem->getQuantity();
        }

        return new ProductDTO(
            id: $product->getId(),
            name: $product->getName(),
            description: $product->getDescription(),
            price: $product->getPrice(),
            categoryName: $product->getCategory()->getName(),
            totalStock: $totalStock,
            isLowStock: $totalStock < 10
        );
    }
}