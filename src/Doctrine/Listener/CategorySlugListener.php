<?php

namespace App\Doctrine;

use App\Entity\Category;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Creates a Slug for new Category
 */
class CategorySlugListener 
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

    public function prePersist(Category $entity)
    {
        if (empty($entity->getSlug())) {
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));   
        }         
    }
}