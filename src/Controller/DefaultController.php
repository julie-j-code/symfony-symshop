<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController{

/**
 *@Route()
 */
    public function index(){
        var_dump("salut") ;
        die;
    }

    public function test(Request $request){
        // dd("test") ;

        // $request=Request::createFromGlobals();
        dd($request);
        $age=$request->query->get("age", 0);
        // si on utilise Request, on retourne forcément une response
        return new Response("vous avez " .$age. " ans");
    }

}

?>