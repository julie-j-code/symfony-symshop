<?php

namespace App\Controller\Purchase;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Repository\PurchaseRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Check Users Payments
 */
class PurchasePaymentController extends AbstractController
{

    /**
     * @Route("/purchase/pay/{id}", name="purchase_payment_form")
     */
    public function showCardForm(int $id, PurchaseRepository $repo)
    {

        $purchase = $repo->find($id);
        if (
            !$purchase
        ) {
            return $this->redirectToRoute("cart_show");
        }

                // This is my real test secret API key.
                Stripe::setApiKey('sk_test_51JP1n9E1UVBLYvWcF8nn6yNlMAPRIGpn4NR4xNYzjuapIuGa3yEJngXWBa0iWFUklkNekQb4blVyA7YJVYdcJbyB009CiYB3yr');

                $intent = PaymentIntent::create([
                    'amount' => $purchase->getTotal(),
                    'currency' => 'eur'
                ]);


        return $this->render('purchase/payment.html.twig', [
            'purchase' => $purchase,
            'clientSecret' => $intent->client_secret
        ]);
    }
}
