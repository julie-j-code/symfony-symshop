<?php

namespace App\Doctrine;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Creates a Slug for new Product
 */
class ProductSlugListener 
{    
    /**
     * slugger
     *
     * @var SluggerInterface
     */
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Product $entity)
    {
        if (empty($entity->getSlug())) {
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }
}