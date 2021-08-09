<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController
{
    protected $logger;
    // première façon de se faire livrer le service :
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     *@Route("/", name="index")
     */
    public function index()
    {
        var_dump("salut");
        die;
    }

    /**
     * @Route("/test/{age<\d+>?0}", name="test", methods={"GET","POST"},schemes={"http", "https"})
     */

    // deuxième façon de se faire passer le service LoggerInterface
    // si je ne veux pas passer par l'injection du constructeur :
     public function test(Request $request, $age, LoggerInterface $logger, Calculator $calculator)
    {
        // si première méthode via le constructeur
        // $this->logger->info("Mon message test de log !");
        // si deuxième méthode ici même
        $logger->info("Mon message test de log !");

        $tva = $calculator->calcule(100);
        dd($tva);
        // dd("test") ;
        // $request=Request::createFromGlobals();
        // dd($request);
        // $age=$request->query->get("age", 0);
        // si on utilise Request, on retourne forcément une response
        return new Response("vous avez " . $age . " ans");
    }
}
