<?php

namespace App\EventDispatcher;

use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Sends an email when a purchase is successful paid
 */
class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface{

    /**
     * logger
     *
     * @var LoggerInterface $logger
     */
    protected  $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            "purchase.success" => 'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseEvent)
    {
        $this->logger->info("Commande n°". $purchaseEvent->getPurchase()->getId().": Email de confirmation envoyé.");
    }

}