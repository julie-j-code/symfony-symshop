<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController{

/**
 *@Route("/", name="index")
 */
    public function index(){
        var_dump("salut") ;
        die;
    }

/**
* @Route("/test/{age<\d+>?0}", name="test", methods={"GET","POST"},schemes={"http", "https"})
*/

    public function test(Request $request, $age){
        // dd("test") ;

        // $request=Request::createFromGlobals();
        // dd($request);
        // $age=$request->query->get("age", 0);
        // si on utilise Request, on retourne forcément une response
        return new Response("vous avez " .$age. " ans");
    }

}

?>