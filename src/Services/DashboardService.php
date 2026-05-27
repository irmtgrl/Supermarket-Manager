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

    public function getTotalProducts(): int
    {
        return $this->productRepository->count([]);
    }

    public function getExpiringProductsCount(): int
    {
        return $this->inventoryItemRepository->countExpiringSoon();
    }
}