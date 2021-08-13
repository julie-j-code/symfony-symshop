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

    public function add($id)
    {
        // 1. je vérifie si j'ai un panier dans la session
        // 2. si j'ai rien, je passe un tableau vide
        $cart = $this->session->get('cart');
        // 3. si le produit $id existe déjà dans le tableau la session
        // 4. je l'incrémante sachant que le tableau ressemblera à
        // 5. sinon je l'ajoute avec la quantité 1
        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        // 6. Enregistrer le tableau mis à jour dans la session
        $this->session->set('cart', $cart);
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->session->get('cart', []) as $id => $qty) {

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
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        $this->session->set('cart', $cart);
    }

    public function decrement($id)
    {
        $cart = $this->session->get('cart', []);

        // 1. Le produit est à 1, il faut donc le supprimer

        if ($cart[$id] === 1) {
            $this->remove($id);
            // on a fini :
            return;
        }

        // 2. Le produit est à plus de 1, il faut le décrémenter
        $cart[$id]--;

        $this->session->set('cart', $cart);
    }

    public function increment($id)
    {
        $cart = $this->session->get('cart', []);

        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        }

        $this->session->set('cart', $cart);
    }

    public function showDetails()
    {
        $detailedCart = [];

        foreach ($this->session->get('cart', []) as $id => $qty) {

            $product = $this->repo->find($id);

            // ajout garde fou si un produit dans la session n'existe plus en base
            if (!$product) {
                continue;
            }

            $detailedCart[] = new CartItem($product, $qty);
        }

        return $detailedCart;
    }
}
