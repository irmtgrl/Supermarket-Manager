<?php 

namespace App\Services;

use App\Repository\InventoryItemRepository;
use App\DTOFactory\InventoryItemDTOFactory;

class ExpirationService
{
    public function __construct(
        private readonly InventoryItemRepository $inventoryItemRepository,
        private readonly InventoryItemDTOFactory $inventoryItemDTOFactory
    ) {}

    public function getExpiringProducts(): array
    {
        $productsExpiresSoon = $this->inventoryItemRepository->findExpiringSoon();
        $expiringProductsDTO = [];

        foreach($productsExpiresSoon as $expiringProducts) {
            $expiringProductsDTO[] = $this->inventoryItemDTOFactory->create($expiringProducts);
        };

        return $expiringProductsDTO;
    }

    public function getExpiredProducts(): array
    {
        $productsExpired = $this->inventoryItemRepository->findExpired();
        $expiredProductsDTO = [];
        
        foreach($productsExpired as $expiredProducts) {
            $expiredProductsDTO[] = $this->inventoryItemDTOFactory->create($expiredProducts);
        };

        return $expiredProductsDTO;
    }
}