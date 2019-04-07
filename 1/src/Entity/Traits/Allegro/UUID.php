<?php

namespace App\Entity\Traits\Allegro;

use Doctrine\ORM\Mapping as ORM;

trait UUID
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $allegroId;

    /**
     * @return string
     */
    public function getAllegroId()
    {
        return $this->allegroId;
    }

    /**
     * @param string $allegroId
     * @return self
     */
    public function setAllegroId($allegroId): self
    {
        $this->allegroId = $allegroId;
        return $this;
    }

}