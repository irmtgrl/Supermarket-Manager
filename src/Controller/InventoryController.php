<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\ExpirationService;

final class InventoryController extends AbstractController
{
    #[Route('/inventory', name: 'app_inventory')]
    public function index(): Response
    {
        return $this->render('inventory/index.html.twig', [
            'controller_name' => 'InventoryController',
        ]);
    }

    #[Route('/inventory/expiring', name:'inventory_expiring')]
    public function showExpiringInventory(
        ExpirationService $expirationService
    ): Response
    {
        $expiringProducts = $expirationService->getExpiringProducts();
        $expiredProducts = $expirationService->getExpiredProducts();

        return $this->render('product/show_expiring_product.html.twig', [
            "expiringProducts" => $expiringProducts,
            "expiredProducts" => $expiredProducts
        ]);
    }
}
