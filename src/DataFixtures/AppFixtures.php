<?php

namespace App\DataFixtures;



use Faker\Factory;
use App\Entity\Product;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        // Use Faker\Factory::create() to create and initialize a Faker generator, which can generate data by accessing methods named after the type of data you want.

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Commerce($faker));
        for ($p = 0; $p < 100; $p++) {
            $product = new Product;
            $product->setName($faker->productName())
                ->setPrice(mt_rand(100, 200))
                ->setSlug($faker->slug());

            $manager->persist($product);
        };

        $manager->flush();
    }
}
