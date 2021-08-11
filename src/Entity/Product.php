<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom du produit est obligatoire.")
     * @Assert\Length(
     *  min=3,
     *  max=255, 
     *  minMessage="La longueur du nom doit être d'au moins {{ limit }} caractères.", 
     *  maxMessage="La longueur du nom doit être au maximum de {{ limit }} caractères."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le prix est obligatoire.")
     * @Assert\GreaterThan(0, message="Le prix minimal du produit doit être positif.")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @Assert\NotBlank(message="La catégorie du produit est obligatoire.")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'url de la photo principale du produit est obligatoire.")
     * @Assert\Url(message="La photo principale doit être une url valide.")
     */
    private $mainPicture;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description courte est obligatoire.")
     * @Assert\Length(
     *  min=20,
     *  max=255, 
     *  minMessage="La longueur de la description courte doit être d'au moins {{ limit }} caractères.", 
     *  maxMessage="La longueur de la description courte doit être au maximum de {{ limit }} caractères."
     * )
     */
    private $shortDescription;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(?string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
