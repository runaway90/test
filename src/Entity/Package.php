<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PackageRepository")
 */
class Package
{
    const PACKAGE_STATUS_CREATED = 'created';
    const PACKAGE_STATUS_IN_TRANSPORT = 'in_transport';
    const PACKAGE_STATUS_DELIVERED = 'delivered';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="packages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address_from;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address_to;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->create_date;
    }

    public function setCreateDate(\DateTimeInterface $create_date): self
    {
        $this->create_date = $create_date;

        return $this;
    }

    public function getAddressFrom(): ?string
    {
        return $this->address_from;
    }

    public function setAddressFrom(string $address_from): self
    {
        $this->address_from = $address_from;

        return $this;
    }

    public function getAddressTo(): ?string
    {
        return $this->address_to;
    }

    public function setAddressTo(string $address_to): self
    {
        $this->address_to = $address_to;

        return $this;
    }
}
