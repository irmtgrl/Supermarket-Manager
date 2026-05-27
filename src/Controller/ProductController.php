<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\ProductService;  

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(
        ProductService $productService
    ): Response
    {
        $products = $productService->searchProducts('');
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }
}
