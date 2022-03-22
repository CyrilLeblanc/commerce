<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouteCollection;

class CatalogController extends AbstractController
{
    public const PRODUCTS_PER_PAGE = 4;

    #[Route('/', name: 'index')]
    public function index()
    {
        return $this->redirectToRoute('catalog');
        new RouteCollection();
    }

    #[Route('/catalog', name: 'catalog')]
    public function catalog(ProductRepository $productRepository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $products = $productRepository->findBy([], null, self::PRODUCTS_PER_PAGE, $this->getOffset($page));
        return $this->render('catalog/index.html.twig', [
            'products' => $products,
            'total_pages' => count($products) / self::PRODUCTS_PER_PAGE,
            'current_page' => $page,
        ]);
    }

    static public function getOffset(int $page): int
    {
        $offset = 0;
        if ($page > 1) {
            $offset = ($page - 1) * self::PRODUCTS_PER_PAGE;
        }
        return $offset;
    }
}
