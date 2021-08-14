<?php

namespace App\Controller\Purchases;

use App\Repository\PurchaseRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;

class PurchaseListController extends AbstractController
{
    protected $security;
    protected $routerInterface;
    protected $environnement;

    function __construct(Security $security, RouterInterface $routerInterface, Environment $twig)
    {
        $this->security = $security;
        $this->routerInterface = $routerInterface;
        $this->environnement = $twig;
    }
    /**
     * Afficher les commandes d'un utilisateur
     *@Route("/purchases", name="purchase_index)
     * @param PurchaseRepository $repo
     * @return void
     */
    public function index(PurchaseRepository $repo)
    {

        // 1 Nous devons nous assurer que la personne est connectée
       /** @var User */
        $user = $this->security->getUser();

        if(!$user){
            $url=$this->router->generate('home');
            return new RedirectResponse($url);
        }

        $html = $this->environnement->render('purchase/index.html.twig', [
            'purchases'=>$user->getPurchases()
        ]);


    }
}
