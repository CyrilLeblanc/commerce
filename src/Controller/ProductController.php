<?php

namespace App\Controller;

use App\Form\PostReviewType;
use App\Repository\ReviewRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductOrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/product/{idProduct}', name: 'product')]
    public function index(
        int $idProduct,
        ProductRepository $productRepository,
        ReviewRepository $reviewRepository,
        ProductOrderRepository $productOrderRepository
    ): Response {
        // display review form or not
        $userReviews = $reviewRepository->findBy(['user' => $this->getUser(), 'product' => $idProduct]);
        $purchased = false;
        foreach($productOrderRepository->findBy(['product' => $idProduct]) as $productOrder) {
            if ($productOrder->getCustomerOrder()->getUser() === $this->getUser()) {
                $purchased = true;
                break;
            }
        }
        $reviewForm = $this->createForm(PostReviewType::class)->createView();

        // review average
        $reviews = $reviewRepository->findBy(['product' => $idProduct]);
        $sum = 0;
        foreach($reviews as $review) {
            $sum += $review->getRate();
        }
        if (count($reviews) > 0) {
            $averageRate = $sum / count($reviews);
        }

        //var_dump($purchased, count($userReviews) == 0); die;

        return $this->render('product/index.html.twig', [
            'product' => $productRepository->find($idProduct),
            'review_form' => $reviewForm,
            'display_review_form' => $purchased && count($userReviews) == 0,
            'reviews' => $reviewRepository->findByProduct($productRepository->find($idProduct)),
            'average_rate' => count($reviews) ? $averageRate : null,
        ]);
    }
}
