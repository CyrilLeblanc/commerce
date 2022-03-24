<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Order;
use App\Entity\ProductOrder;
use App\Form\CheckoutType;
use App\Repository\CardRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductOrderRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $products = [];
        $cart = $request->getSession()->get('cart', []);
        foreach ($cart as $idProduct => $quantity) {
            $products[] = $productRepository->find($idProduct)->setQuantity($quantity);
        }

        return $this->render('cart/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/cart/add', name: 'cart_add')]
    public function add(Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);
        $cart[$request->request->getInt('id')] = $request->request->getInt('quantity');
        $session->set('cart', $cart);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove', name: 'cart_remove')]
    public function remove(Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);
        unset($cart[$request->request->getInt('id')]);
        $session->set('cart', $cart);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/checkout', name: 'cart_checkout')]
    public function checkout(
        Request $request,
        ProductRepository $productRepository,
        CardRepository $cardRepository,
        ProductOrderRepository $productOrderRepository,
        OrderRepository $orderRepository
    ): Response {
        $card = new \App\Dto\Card();
        $form = $this->createForm(CheckoutType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($card->getYear() < date('Y') || $card->getYear() > date('Y') + 10) {
                $form->get('year')->addError(new FormError('Invalid year'));
            } else {
                if ($card->getMonth() < date('m')) {
                    $form->get('month')->addError(new FormError('Invalid month'));
                }
            }
            if ($form->isValid()) {

                // save card
                $cardEntity = new Card();
                $cardEntity->setDate(sprintf("%d/%d", $card->getMonth(), $card->getYear()))
                    ->setNumber(substr($card->getNumber(), -4))
                    ->setUser($this->getUser())
                    ->setCvv($card->getCvv());
                $cardRepository->add($cardEntity);

                // create order
                $order = new Order();
                $order->setUser($this->getUser())
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable())
                    ->setStatus(Order::STATUS_NEW)
                    ->setCard($cardEntity);
                $orderRepository->add($order);

                // create product orders
                $cart = $request->getSession()->get('cart', []);
                foreach ($cart as $idProduct => $quantity) {
                    $productOrder = new ProductOrder();
                    $productOrder->setProduct($productRepository->find($idProduct))
                        ->setQuantity($quantity);
                    $productOrderRepository->add($productOrder);
                    $order->addProductOrder($productOrder);
                    $orderRepository->add($order);
                }
                return $this->redirectToRoute('cart_checkout_resume', ['orderId' => $order->getId()]);
            }
        }

        $products = [];
        $cart = $request->getSession()->get('cart', []);
        foreach ($cart as $idProduct => $quantity) {
            $products[] = $productRepository->find($idProduct)->setQuantity($quantity);
        }

        return $this->render('cart/checkout.html.twig', [
            'cart' => $cart,
            'products' => $products,
            'form_checkout' => $form->createView(),
        ]);
    }

    #[Route('/cart/checkout/resume/{orderId}', name: 'cart_checkout_resume')]
    public function checkoutResume(Request $request, $orderId, OrderRepository $orderRepository, ProductRepository $productRepository): Response
    {
        $order = $orderRepository->findOneBy(['id' => $orderId, 'user' => $this->getUser()]);
        $products = [];
        foreach ($order->getProductOrders() as $productOrder) {
            $products[] = $productOrder->getProduct()->setQuantity($productOrder->getQuantity());
        }

        return $this->render('cart/checkout_resume.html.twig', [
            'order' => $order,
            'products' => $products,
        ]);
    }

    #[Route('/cart/checkout/validate/{idOrder}', name: 'cart_checkout_validate')]
    public function validatePurchase(Request $request, $idOrder, OrderRepository $orderRepository, ProductRepository $productRepository): Response
    {
        $order = $orderRepository->findOneBy(['id' => $idOrder, 'user' => $this->getUser()]);
        $order->setStatus(Order::STATUS_PAID);
        $orderRepository->add($order);
        $request->getSession()->remove('cart');
        return $this->redirectToRoute('cart');
    }
}
