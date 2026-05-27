<?php

namespace App\Services;

use App\Repository\ProductRepository;
use App\DTOFactory\ProductDTOFactory;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductDTOFactory $productDTOFactory
    ){}

    public function searchProducts(string $term): array
    {
        $products = $this->productRepository->searchByName($term);

        $productDTOs = [];

        foreach($products as $product) {
            $productDTOs[] = $this->productDTOFactory->create($product);
        }

        return $productDTOs;
    }
}