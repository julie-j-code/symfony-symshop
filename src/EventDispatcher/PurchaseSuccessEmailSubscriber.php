<?php

namespace App\EventDispatcher;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use App\Event\PurchaseSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;
/**
 * Sends an email when a purchase is successful paid
 */
class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{

    protected  $logger;
    protected $mailer;
    protected $security;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            "purchase.success" => 'sendSuccessEmail'
        ];
    }

    /**
     * Envoie un successful orderemail à user
     *
     * @param  mixed $purchaseEvent
     * @return void
     */

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseEvent)
    {
        // 1 Récupérer l'utilisateur actuellement en ligne pour connaitre son email
        // Security
        /** @var User */
        $currentUser = $this->security->getUser();

        // 2 Récupérer la commande (via PurchaseSuccesEvent)
        $purchase = $purchaseEvent->getPurchase();
        // pourquoi pas ? $currentUser = $purchase->getUser();

        // 3 Ecrire le mail
        $email = new TemplatedEmail();
        $email->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
            ->from("julie@mail.com")
            ->subject("Confirmation de votre commande n°".$purchase->getId())
            ->text("Votre commande n°".$purchase->getId()." est bien en route.")
            ->htmlTemplate('emails/purchase_success.html.twig')
            ->context([
                'purchase' => $purchase,
                'currentUser' => $currentUser
            ])
        ;

        // 4 Envoyer l'email
        $this->mailer->send($email);

        $this->logger->info("Commande n°" . $purchaseEvent->getPurchase()->getId() . ": Email de confirmation envoyé.");
    }
}
