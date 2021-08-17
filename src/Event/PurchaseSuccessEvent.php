<?php

namespace App\Event;

use App\Entity\Purchase;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * PurchaseScuccessEvent
 */
class PurchaseSuccessEvent extends Event
{    
    /**
     * purchase Instance
     *
     * @var Purchase $purchase
     */
    private $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * Get the value of Purchase
     */ 
    public function getPurchase(): Purchase
    {
        return $this->purchase;
    }
}