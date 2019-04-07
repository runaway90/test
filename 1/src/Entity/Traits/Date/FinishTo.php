<?php
namespace App\Entity\Traits\Date;

trait FinishTo
{
    /**
     * @ORM\Column(type="string")
     */
    private $finishTo;

    public function getFinishTo(): ?string
    {
        return $this->finishTo;
    }

    public function setFinishTo(string $finishTo): self
    {
        $this->finishTo = $finishTo;
        return $this;
    }
}