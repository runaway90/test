<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllegroPhotoRepository")
 */
class AllegroPhoto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AllegroUserAccounts", inversedBy="allegroPhotos")
     */
    private $allegroUser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $allegroLink;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $coreLink;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AllegroOffer", inversedBy="photos")
     */
    private $allegroOffer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAllegroUser(): ?AllegroUserAccounts
    {
        return $this->allegroUser;
    }

    public function setAllegroUser(?AllegroUserAccounts $allegroUser): self
    {
        $this->allegroUser = $allegroUser;

        return $this;
    }

    public function getAllegroLink(): ?string
    {
        return $this->allegroLink;
    }

    public function setAllegroLink(?string $allegroLink): self
    {
        $this->allegroLink = $allegroLink;

        return $this;
    }

    public function getCoreLink(): ?string
    {
        return $this->coreLink;
    }

    public function setCoreLink(string $coreLink): self
    {
        $this->coreLink = $coreLink;

        return $this;
    }

    public function getAllegroOffer(): ?AllegroOffer
    {
        return $this->allegroOffer;
    }

    public function setAllegroOffer(?AllegroOffer $allegroOffer): self
    {
        $this->allegroOffer = $allegroOffer;

        return $this;
    }
}
