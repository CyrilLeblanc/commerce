<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
