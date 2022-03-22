<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    #[Route('/review/{idProduct}/', name: 'review_add', methods: ['POST'])]
    public function postReview(int $idProduct,ProductRepository $productRepository, Request $request): Response
    {
        $product = new Review();
        $product->setTitle($request->request->get('title'))
            ->setContent($request->request->get('content'))
            ->setRate($request->request->get('rate'))
            ->setUser($this->getUser());

        $productRepository->find($idProduct)->addReview($product);
        var_dump($product); die;
    }
}
