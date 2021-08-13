<?php

namespace App\Cart;

use App\Repository\ProductRepository;
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

            $total += $product->getPrice() * $qty;
        }

        return $total;
    }
    public function showDetails()
    {
        $detailedCart = [];

        foreach ($this->session->get('cart', []) as $id => $qty) {

            $product = $this->repo->find($id);

            $detailedCart[] = [
                'product' => $product,
                'quantity' => $qty
            ];
        }

        return $detailedCart;
    }
}
