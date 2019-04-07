<?php

namespace App\Entity;

use App\Entity\Traits\Allegro\UUID;
use App\Entity\Traits\Date\CreatedAt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllegroOfferRepository")
 */
class AllegroOffer
{
    use CreatedAt;
    use UUID;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AllegroPhoto", mappedBy="allegroOffer")
     */
    private $photos;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AllegroCategories", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AllegroOrder", inversedBy="offers")
     */
    private $allegroOrder;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|AllegroPhoto[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(AllegroPhoto $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAllegroOffer($this);
        }

        return $this;
    }

    public function removePhoto(AllegroPhoto $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getAllegroOffer() === $this) {
                $photo->setAllegroOffer(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?AllegroCategories
    {
        return $this->category;
    }

    public function setCategory(AllegroCategories $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAllegroOrder(): ?AllegroOrder
    {
        return $this->allegroOrder;
    }

    public function setAllegroOrder(?AllegroOrder $allegroOrder): self
    {
        $this->allegroOrder = $allegroOrder;

        return $this;
    }
}
