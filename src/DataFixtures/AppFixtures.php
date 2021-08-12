<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use Bezhanov\Faker\Provider\Commerce;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    protected $slugger;
    protected $encoder;
    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        // Use Faker\Factory::create() to create and initialize a Faker generator, which can generate data by accessing methods named after the type of data you want.

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Commerce($faker));
        // nouveau provider auquel on passe l'instance de Faker pour qu'elle soit enrichie de ce nouveau provider
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $admin = new User;
        $hash = $this->encoder->encodePassword($admin, 'password');

        $admin->setEmail('jeannet.julie@gmail.com')
              ->setPassword($hash)
              ->setFullName("admin")
              ->setRoles(['ROLE_ADMIN']);
        
        $manager->persist($admin);


        
        for ($u=0; $u < 5; $u++) { 
            $user = new User;
            $hash = $this->encoder->encodePassword($user, 'password');
            $user->setEmail("user$u@gmail.com")
                 ->setPassword($hash)
                 ->setFullName($faker->name());
            $manager->persist($user);
        }

        
        for ($c = 0; $c < 3; $c++) {
            $category = new Category;
            $category->setName($faker->department)
                ->setSlug(strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(15,20); $p++) {
                $product = new Product;
                $product->setName($faker->productName())
                    ->setPrice(mt_rand(100, 200))
                    // ->setSlug($faker->slug());
                    ->setSlug(strtolower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400,400,true));

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
