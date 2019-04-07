<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllegroOrderRepository")
 */
class AllegroOrder
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $buyerId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $checkoutFormId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AllegroOffer", mappedBy="allegroOrder")
     */
    private $offers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AllegroEvent", inversedBy="allegroOrders")
     */
    private $allegroEvents;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuyerId(): ?int
    {
        return $this->buyerId;
    }

    public function setBuyerId(int $buyerId): self
    {
        $this->buyerId = $buyerId;

        return $this;
    }

    public function getCheckoutFormId(): ?string
    {
        return $this->checkoutFormId;
    }

    public function setCheckoutFormId(string $checkoutFormId): self
    {
        $this->checkoutFormId = $checkoutFormId;

        return $this;
    }

    /**
     * @return Collection|AllegroOffer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(AllegroOffer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setAllegroOrder($this);
        }

        return $this;
    }

    public function removeOffer(AllegroOffer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getAllegroOrder() === $this) {
                $offer->setAllegroOrder(null);
            }
        }

        return $this;
    }

    public function getAllegroEvents(): ?AllegroEvent
    {
        return $this->allegroEvents;
    }

    public function setAllegroEvents(?AllegroEvent $allegroEvents): self
    {
        $this->allegroEvents = $allegroEvents;

        return $this;
    }
}
