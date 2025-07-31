<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Advertisement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string", length:255)]
    private string $title;

    #[ORM\Column(type:"text")]
    private string $description;

    #[ORM\Column(type:"float")]
    private float $price;

    #[ORM\OneToMany(mappedBy:"advertisement", targetEntity:Image::class, cascade:["persist", "remove"], orphanRemoval:true)]
    private $images;

    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int { 
        return $this->id; 
    }

    public function getTitle(): ?string { 
        return $this->title;
    }

    public function setTitle(string $title): self { 
        $this->title = $title; 
        return $this; 
    }

    public function getDescription(): ?string { 
        return $this->description; 
    }

    public function setDescription(string $desc): self { 
        $this->description = $desc; 
        return $this; 
    }

    public function getPrice(): ?float { 
        return $this->price; 
    }

    public function setPrice(float $price): self { 
        $this->price = $price; 
        return $this;
    }

    public function getImages() {
        return $this->images; 
    }

    public function addImage(Image $img): self { 
        if (!$this->images->contains($img)) {
            $this->images[] = $img; 
            $img->setAdvertisement($this);
        }

        return $this;
    }
    public function removeImage(Image $img): self { 
        if ($this->images->removeElement($img)) { 
            if ($img->getAdvertisement() === $this) { 
                $img->setAdvertisement(null);
            }
        } 

        return $this;
    }
}