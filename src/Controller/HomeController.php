<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(ProductRepository $repo): Response
    {
        // je veux tous les produits, mais avec des critères
        // sans critères de recherche particuliers ni d'ordonnancement, mais avec une limite 

        $products=$repo->findBy([],[], 3);
        return $this->render('home.html.twig',[
            "products"=>$products
        ]);
    }
}
