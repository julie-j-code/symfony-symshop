<?php

namespace App\Controller\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Purchase\PurchasePersister;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * This controller handles the purchase and create it if its valid
 */
class PurchaseConfirmationController extends AbstractController
{

    /**
     * CartService instance
     *
     * @var CartService $cartService
     */
    protected $cartService;
    /**
     * EntityManagerInterface instance
     *
     * @var EntityManagerInterface $em
     */
    protected $em;
    /**
     * PurchasePersister instance
     *
     * @var PurchasePersister $persister
     */
    protected $persister;

    public function __construct(CartService $cartService, EntityManagerInterface $em, PurchasePersister $persister)
    {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->persister = $persister;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm", priority=4)
     * @IsGranted("ROLE_USER", message="vous devez être connecté pour passer une commande.")
     */
    public function confirm(Request $request)
    {
        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $this->addFlash("warning", "Vous devez renseigner votre adresse via le formulaire.");
            return $this->redirectToRoute("cart_show");
        }

        $cartItems = $this->cartService->showDetails();

        if (count($cartItems) === 0) {
            $this->addFlash("warning", "Vous ne pouvez pas passer de commande avec un panier vide.");
            return $this->redirectToRoute("cart_show");
        }

        /** @var Purchase */
        $purchase = $form->getData();
        $this->persister->storePurchase($purchase);

        // maintenant qu'on souhaite faire une redirection vers l'interface de paiement :
        // $this->cartService->empty();
        // $this->addFlash('success', 'La commande a bien été enregistrée.');
        // return $this->redirectToRoute("purchase_index");
        return $this->redirectToRoute("purchase_payment_form", [
            'id' => $purchase->getId()
        ]);
    }
}
