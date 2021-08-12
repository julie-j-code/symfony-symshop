<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function index($id, ProductRepository $repo, SessionInterface $session): Response
    {
        // 0. S'assurer que le  produit existe
        $product = $repo->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Le produit demandé n'existe pas et ne peut donc pas être ajouté.");
        }

        // 1. je vérifie si j'ai un panier dans la session
        // 2. si j'ai rien, je passe un tableau vide
        // $cart = $request->getSession()->get('cart', []);
        // ou si je me fais livrer la SessionInterface :
        $cart = $session->get('cart');
        // je n'ai plus besoin de la Request  !
        // 3. si le produit $id existe déjà dans le tableau la session
        // 4. je l'incrémante sachant que le tableau ressemblera à
        // ["produit1"=>2, "produit2"=>5]
        // 5. sinon je l'ajoute avec la quantité 1
        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        // 6. Enregistrer le tableau mis à jour dans la session
        $session->set('cart', $cart);
        // dd($session->get('cart'));
        return $this->redirectToRoute("product_show", [
            "category_slug" => $product->getCategory()->getSlug(),
            "slug" => $product->getSlug()
        ]);
    }
}
