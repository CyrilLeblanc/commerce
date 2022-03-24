<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    #[Route('/admin/order', name: 'admin_order')]
    public function order(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/order.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    #[Route('/admin/order/ship/{idOrder}', name: 'admin_order_ship')]
    public function sendOrder(int $idOrder, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->find($idOrder)->setStatus(Order::STATUS_TRANSIT);
        $orderRepository->add($order);
        return $this->redirectToRoute('admin_order');
    }
}
