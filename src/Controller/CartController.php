<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function index($id, ProductRepository $repo, CartService $cartService): Response
    {
        // 0. S'assurer que le  produit existe
        $product = $repo->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Le produit demandé n'existe pas et ne peut donc pas être ajouté.");
        }

        $cartService->add($id);

        /** @var FlashBag */
        $this->addFlash('success', "Le produit a bien été ajouté au panier.");

        return $this->redirectToRoute("product_show", [
            "category_slug" => $product->getCategory()->getSlug(),
            "slug" => $product->getSlug()
        ]);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id":"\d+"})
     */
    public function delete($id, ProductRepository $repo, CartService $cartService): Response
    {
        $product = $repo->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Le produit demandé n'existe pas et ne peut donc pas être ajouté.");
        }

        $cartService->remove($id);

        $this->addFlash('success', "Le produit a bien été retiré du panier.");

        return $this->redirectToRoute("cart_show");
    }
    /**
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements={"id":"\d+"})
     */
    public function decrement($id, ProductRepository $repo, CartService $cartService): Response
    {
        $product = $repo->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Le produit demandé n'existe pas et ne peut donc pas être ajouté.");
        }

        $cartService->decrement($id);

        $this->addFlash('success', "La quantité a bien été diminuée.");

        return $this->redirectToRoute("cart_show");
    }
    /**
     * @Route("/cart/increment/{id}", name="cart_increment", requirements={"id":"\d+"})
     */
    public function increment($id, ProductRepository $repo, CartService $cartService): Response
    {
        $product = $repo->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Le produit demandé n'existe pas et ne peut donc pas être ajouté.");
        }

        $cartService->increment($id);

        $this->addFlash('success', "La quantité a bien été augmentée.");

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(CartService $cartService)
    {

        $detailedCart = $cartService->showDetails();
        $total = $cartService->getTotal();

        return $this->render("cart/index.html.twig", [
            "items" => $detailedCart,
            "total" => $total
        ]);
    }
}
