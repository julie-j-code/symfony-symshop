<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($p = 0; $p < 100; $p++) {
            $product = new Product;
            $product->setName("Produit n° $p")
                ->setPrice(mt_rand(100, 200))
                ->setSlug("product-n-$p");

            $manager->persist($product);
        };

        $manager->flush();
    }
}
