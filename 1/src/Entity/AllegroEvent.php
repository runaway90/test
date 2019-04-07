<?php

namespace App\Entity;

use App\Entity\Traits\Allegro\UUID;
use App\Entity\Traits\Date\CreatedAt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllegroEventRepository")
 */
class AllegroEvent
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
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AllegroUserAccounts", inversedBy="allegroEvents")
     */
    private $userAccount;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AllegroOrder", mappedBy="allegroEvents")
     */
    private $allegroOrders;

    public function __construct()
    {
        $this->allegroOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUserAccount(): ?AllegroUserAccounts
    {
        return $this->userAccount;
    }

    public function setUserAccount(?AllegroUserAccounts $userAccount): self
    {
        $this->userAccount = $userAccount;

        return $this;
    }

    /**
     * @return Collection|AllegroOrder[]
     */
    public function getAllegroOrders(): Collection
    {
        return $this->allegroOrders;
    }

    public function addAllegroOrder(AllegroOrder $allegroOrder): self
    {
        if (!$this->allegroOrders->contains($allegroOrder)) {
            $this->allegroOrders[] = $allegroOrder;
            $allegroOrder->setAllegroEvents($this);
        }

        return $this;
    }

    public function removeAllegroOrder(AllegroOrder $allegroOrder): self
    {
        if ($this->allegroOrders->contains($allegroOrder)) {
            $this->allegroOrders->removeElement($allegroOrder);
            // set the owning side to null (unless already changed)
            if ($allegroOrder->getAllegroEvents() === $this) {
                $allegroOrder->setAllegroEvents(null);
            }
        }

        return $this;
    }
}
