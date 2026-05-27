<?php

namespace App\Services;

use App\Repository\ProductRepository;
use App\Repository\InventoryItemRepository;

class DashboardService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly InventoryItemRepository $inventoryItemRepository
    ) {}

    /**
     * Returns the total number of unique products registered in the store.
     */
    public function getTotalProducts(): int
    {
        // Built-in Doctrine method to quickly count all records
        return $this->productRepository->count([]);
    }

    /**
     * Returns the total count of items expiring within the next 7 days.
     */
    public function getExpiringProductsCount(): int
    {
        // We call the repository custom query method we will build next
        return $this->inventoryItemRepository->countExpiringSoon();
    }
}