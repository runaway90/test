<?php

namespace App\Entity\Traits\Allegro;

trait Name
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $allegroName;

    /**
     * @return string
     */
    public function getAllegroName()
    {
        return $this->allegroName;
    }

    /**
     * @param string $allegroName
     * @return self
     */
    public function setAllegroName($allegroName): self
    {
        $this->allegroName = $allegroName;
        return $this;

    }
}