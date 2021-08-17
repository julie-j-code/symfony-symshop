<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Event\PurchaseSuccessEvent;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
    public function success(int $id, PurchaseRepository $repo, EntityManagerInterface $em, CartService $cartService, EventDispatcherInterface $dispatcher)
    {

        $purchase = $repo->find($id);
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

        $cartService->empty();

        $purchaseEvent = new PurchaseSuccessEvent($purchase);
        $dispatcher->dispatch($purchaseEvent, "purchase.success");



        // 4 je redirige avec un message de succes vers la liste des commandes
        $this->addFlash('success', "la commande a bien été finalisée");
        return $this->redirectToRoute('purchase_index');
    }
}
