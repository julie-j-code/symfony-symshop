<?php

namespace App\Stripe;

use App\Entity\Purchase;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Symfony\Component\Console\Input\StringInput;

class StripeService{

    // test secret API key
    protected $secretKey;
    protected $publicKey;

public function __construct(string $secretKey,string $publicKey)
{
    $this->secretKey = $secretKey;
    $this->publicKey = $publicKey;
}

    public function getPaymentIntent(Purchase $purchase){
         // This is my real test secret API key.
         Stripe::setApiKey($this->secretKey);

         return $intent = PaymentIntent::create([
             'amount' => $purchase->getTotal(),
             'currency' => 'eur'
         ]);
    }
}