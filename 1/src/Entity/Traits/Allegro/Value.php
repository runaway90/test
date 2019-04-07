<?php

namespace App\Entity\Traits\Allegro;

trait Value
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @return string
     */
    public function getAllegroValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return self
     */
    public function setAllegroValue($value): self
    {
        $this->value = $value;
        return $this;

    }
}