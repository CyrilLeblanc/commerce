<?php

namespace App\Controller;

use App\Form\CheckoutType;
use App\Repository\ProductRepository;
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
    public function checkout(Request $request, ProductRepository $productRepository): Response
    {
        $card = new \App\Dto\Card();
        $form = $this->createForm(CheckoutType::class, $card);
        $form->handleRequest($request);


        $cart = $request->getSession()->get('cart', []);
        foreach ($cart as $idProduct => $quantity) {
            $products[] = $productRepository->find($idProduct)->setQuantity($quantity);
        }

        return $this->render('cart/checkout.html.twig', [
            'cart' => $cart,
            'products' => $products,
            'form_checkout' => $this->createForm(CheckoutType::class)->createView(),
        ]);
    }
}
