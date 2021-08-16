<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use App\Service\Cart\CartItem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    // A l'intitialisation du service, on se fait livrer la SessionInterface
    // Pour ne pas avoir besoin de la passer en argument de la function add

    protected $session;
    protected $repo;

    public function __construct(SessionInterface $session, ProductRepository $repo)
    {
        $this->session = $session;
        $this->repo = $repo;
    }

    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }
    
    protected function setCart(array $cart)
    {
        $this->session->set('cart', $cart);
    }

    public function add($id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }

        $cart[$id]++;

        $this->setCart($cart);
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->getCart() as $id => $qty) {

            $product = $this->repo->find($id);
            // ajout garde fou si un produit dans la session n'existe plus en base
            if (!$product) {
                continue;
            }

            $total += $product->getPrice() * $qty;
        }

        return $total;
    }

    public function remove($id)
    {
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->setCart($cart);
    }

    public function decrement($id)
    {
        $cart = $this->getCart();

        // 1. Le produit est à 1, il faut donc le supprimer

        if ($cart[$id] === 1) {
            $this->remove($id);
            // on a fini :
            return;
        }

        // 2. Le produit est à plus de 1, il faut le décrémenter
        $cart[$id]--;

        $this->setCart($cart);
    }

    public function increment($id)
    {
        $cart = $this->getCart();

        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        }

        $this->setCart($cart);
    }

    public function showDetails()
    {
        $detailedCart = [];

        foreach ($this->getCart() as $id => $qty) {

            $product = $this->repo->find($id);

            // ajout garde fou si un produit dans la session n'existe plus en base
            if (!$product) {
                continue;
            }

            $detailedCart[] = new CartItem($product, $qty);
        }

        return $detailedCart;
    }


    protected function saveCart(array $cart)
    {
        $this->session->set('cart', $cart);
    }

    public function empty()
    {
        $this->saveCart([]);
    }

    
}
