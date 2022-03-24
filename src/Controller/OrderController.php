<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{

    public function __construct(
        private OrderRepository $orderRepository
    ) {
    }

    #[Route('/order', name: 'order')]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $this->orderRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/order/delivered/{idOrder}', name: 'order_delivered')]
    public function delivered(int $idOrder): Response
    {
        $order = $this->orderRepository->find($idOrder)->setStatus(Order::STATUS_DELIVERED);
        $this->orderRepository->add($order);
        return $this->redirectToRoute('order');
    }
}
