<?php

namespace App\Taxes;

class Calculator{

    public function calcule(float $prix):float{
        return $prix*(20/100);

    }
}

?>