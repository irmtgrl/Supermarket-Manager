<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\ProductService;  
use App\DTOFactory\ProductDTOFactory;
use App\Repository\ProductRepository;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_product')]
    public function index(
        ProductService $productService
    ): Response
    {
        $products = $productService->searchProducts('');
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/product/{id}', name: 'product_show')]
    public function showProduct(
        int $id,
        ProductRepository $repository,
        ProductDTOFactory $productDTOFactory
    ): Response
    {
        $product = $repository->find($id);
        $productDTO = $productDTOFactory->create($product);

        if(!$product) {
            throw $this->createNotFouncException();
        };

        return $this->render('product/show_single_product.html.twig', [
            'product' => $productDTO
        ]);
    }

    #[Route('/products/search', name: 'product_search')]
    public function searchProduct(
        Request $request,
        ProductService $productService
    ): Response
    {
        $term = $request->query->get('q', '');

        $products = $productService->searchProducts($term);

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }
}
