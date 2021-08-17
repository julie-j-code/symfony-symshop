<?php

namespace App\Controller\Purchase;

use Stripe\Stripe;
use App\Entity\Purchase;
use Stripe\PaymentIntent;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Check Users Payments
 */
class PurchasePaymentController extends AbstractController
{

    /**
     * @Route("/purchase/pay/{id}", name="purchase_payment_form")
     * $IsGranted("ROLE_USER")
     */
    public function showCardForm(int $id, PurchaseRepository $repo, StripeService $stripe)
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

        //    on fait maintenant appel au service dédié
        $intent = $stripe->getPaymentIntent($purchase);

        return $this->render('purchase/payment.html.twig', [
            'purchase' => $purchase,
            'clientSecret' => $intent->client_secret
        ]);
    }
}
