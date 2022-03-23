<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\PostReviewType;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    #[Route('/review/new', name: 'review_add', methods: ['POST'])]
    public function postReview(ProductRepository $productRepository, ReviewRepository $reviewRepository, Request $request): Response
    {
        $review = new Review();
        $form = $this->createForm(PostReviewType::class, $review)->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted() && $product = $productRepository->find($request->get('idProduct'))) {
            $review->setUser($this->getUser());
            $review->setProduct($product);
            $reviewRepository->add($review);
        }
        return $this->redirectToRoute('product', ['idProduct' => $product->getId()]);
    }
}
