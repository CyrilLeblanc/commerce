<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/product/{idProduct}', name: 'product')]
    public function index(int $idProduct, ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'product' => $productRepository->find($idProduct),
        ]);
    }
}
