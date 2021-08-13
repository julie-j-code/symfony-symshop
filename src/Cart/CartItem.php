<?php

namespace App\Service\Cart;

use App\Entity\Product;

/**
 * CarItem represente un item du Panier
 */
class CartItem {
    
    public $product;
        
    public $qty;

    public function __construct(Product $product, int $qty)
    {
     $this->product = $product;
     $this->qty = $qty;   
    }
    
    public function getTotal(): int
    {
        return $this->product->getPrice() * $this->qty;
    }

}