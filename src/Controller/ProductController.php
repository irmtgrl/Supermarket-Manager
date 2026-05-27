<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Form\ProductType;
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

    #[Route('/products/add', name: 'product_new')]
    public function addProduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product saved successfully!');

            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/add_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/products/{id}', name: 'product_show')]
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
