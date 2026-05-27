<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\DashboardService;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(
        DashboardService $dashboardService
    ): Response {

        return $this->render(
            'dashboard/index.html.twig',
            [
                'totalProducts' =>
                    $dashboardService->getTotalProducts(),

                'expiringProducts' =>
                    $dashboardService->getExpiringProductsCount(),
            ]
        );
    }
}
