<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Check Users Payments
 */
class PurchasePaymentSuccessController extends AbstractController
{

    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
     * $IsGranted("ROLE_USER")
     */
    public function success(int $id, PurchaseRepository $repo, EntityManagerInterface $em, CartService $cartService)
    {

        // 1 Je récupère la commande
        $purchase = $repo->find($id);
        // 2 je la fais passer au status PAID
        if (
            !$purchase
            || $purchase && $purchase->getUser() !== $this->getUser()
            || $purchase->getStatus() === Purchase::STATUS_PAID
        ) {
            $this->addFlash('message', "cette commande n'existe  pas");
            return $this->redirectToRoute("cart_show");
        }

        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();
        // 3 je vide le panier
        $cartService->empty();


        // 4 je redirige avec un message de succes vers la liste des commandes
        $this->addFlash('success', "la commande a bien été finalisée");
        return $this->redirectToRoute('purchase_index');
    }
}
