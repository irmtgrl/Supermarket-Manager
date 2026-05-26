<?php

namespace App\DTO;

class InventoryItemDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $quantity,
        public readonly string $expirationDate,
        public readonly string $purchasePrice,
        public readonly bool $isExpired,
        public readonly bool $expiresSoon,
    ){
    }
}