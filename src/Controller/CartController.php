<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends AbstractController

{
    protected $repo;
    protected $cartService;
    
    public function __construct(ProductRepository $repo, CartService $cartService ){
        $this->repo = $repo;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/cart/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function index($id): Response
    {
        // 0. S'assurer que le  produit existe
        $product = $this->repo->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Le produit demandé n'existe pas et ne peut donc pas être ajouté.");
        }

        $this->cartService->add($id);

        $this->addFlash('success', "Le produit a bien été ajouté au panier.");

        return $this->redirectToRoute("product_show", [
            "category_slug" => $product->getCategory()->getSlug(),
            "slug" => $product->getSlug()
        ]);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id":"\d+"})
     */

    public function delete($id): Response
    {
        $product = $this->repo->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Le produit demandé n'existe pas et ne peut donc pas être ajouté.");
        }

        $this->cartService->remove($id);

        $this->addFlash('success', "Le produit a bien été retiré du panier.");

        return $this->redirectToRoute("cart_show");
    }
    
    /**
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements={"id":"\d+"})
     */

    public function decrement($id): Response
    {
        $product = $this->repo->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Le produit demandé n'existe pas et ne peut donc pas être ajouté.");
        }

        $this->cartService->decrement($id);

        $this->addFlash('success', "La quantité a bien été diminuée.");

        return $this->redirectToRoute("cart_show");
    }
    /**
     * @Route("/cart/increment/{id}", name="cart_increment", requirements={"id":"\d+"})
     */

    public function increment($id): Response
    {
        $product = $this->repo->find($id);
        if (!$product) {
            throw new NotFoundHttpException("Le produit demandé n'existe pas et ne peut donc pas être ajouté.");
        }

        $this->cartService->increment($id);

        $this->addFlash('success', "La quantité a bien été augmentée.");

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/cart", name="cart_show")
     */

    public function show()
    {

        $detailedCart = $this->cartService->showDetails();
        $total = $this->cartService->getTotal();

        return $this->render("cart/index.html.twig", [
            "items" => $detailedCart,
            "total" => $total
        ]);
    }
}
