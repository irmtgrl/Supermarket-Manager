<?php

namespace App\DTO;

class ProductDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $barcode,
        public readonly string $description,
        public readonly string $price,
        public readonly string $categoryName,
        public readonly int $totalStock,
        public readonly bool $isLowStock,
    ) {
    }
}