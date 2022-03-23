<?php

namespace App\Controller;

use App\Form\PostReviewType;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    #[Route('/product/{idProduct}', name: 'product')]
    public function index(int $idProduct, ProductRepository $productRepository, ReviewRepository $reviewRepository, Request $request): Response
    {
        $reviewForm = $this->createForm(PostReviewType::class)->createView();
        return $this->render('product/index.html.twig', [
            'product' => $productRepository->find($idProduct),
            'review_form' => $reviewForm,
            'reviews' => $reviewRepository->findByProduct($productRepository->find($idProduct)),
        ]);
    }
}
