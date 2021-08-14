<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Vérifie si l'utilisateur est connecté et le redirige vers l'historique de ses commandes
 */
class PurchasesListController extends AbstractController{

    /**
     * @Route("/purchases", name="purchase_index")
     * @IsGranted("ROLE_USER", message="Vous devez être authentifié pour pouvoir accéder à vos commandes.")
     */
    public function index(): Response
    {
        /**@var User */
        $user= $this->getUser();

        return $this->render("purchase/index.html.twig", [
            "purchases" => $user->getPurchases()
        ]);
    }
}