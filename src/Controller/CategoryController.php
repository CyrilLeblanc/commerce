<?php

namespace App\Controller;

use App\Controller\CatalogController;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category_list')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/category/{idCategory}', name: 'catalog_by_category')]
    public function categoryId(CategoryRepository $categoryRepository, Request $request, int $idCategory): Response
    {
        $page = $request->query->getInt('page', 1);
        $category = $categoryRepository->find($idCategory);
        $products = $category->getProducts(CatalogController::getOffset($page), CatalogController::PRODUCTS_PER_PAGE);

        return $this->render('catalog/index.html.twig', [
            'current_page' => $page,
            'products' => $products,
            'total_pages' => count($products) / CatalogController::PRODUCTS_PER_PAGE,
        ]);
    }
}
