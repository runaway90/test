<?php
namespace App\Entity\Traits\Date;

trait CreatedAt
{
    /**
     * @ORM\Column(type="string")
     */
    private $createdAt;

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreateAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

}