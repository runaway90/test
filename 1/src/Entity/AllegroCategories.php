<?php

namespace App\Entity;

use App\Entity\Traits\Allegro\Name;
use App\Entity\Traits\Date\CreatedAt;
use App\Entity\Traits\Allegro\UUID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 */
class AllegroCategories
{
    use CreatedAt;
    use UUID;
    use Name;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AllegroCategories", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true);
     */
    private $allegroParentCategory;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $advertisement;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $advertisementPriceOptional;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $leaf;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AllegroParameter", mappedBy="allegroCategory", cascade={"persist","remove"})
     */
    private $parameters;

    public function __construct()
    {
        $this->parameters = new ArrayCollection();
    }

    /**
     * @return boolean
     */
    public function getLeaf()
    {
        return $this->leaf;
    }

    /**
     * @param boolean $leaf
     * @return self
     */
    public function setLeaf($leaf): self
    {
        $this->leaf = $leaf;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return self
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getAllegroParentCategory()
    {
        return $this->allegroParentCategory;
    }

    /**
     * @param mixed $allegroParentCategory
     * @return self
     */
    public function setAllegroParentCategory($allegroParentCategory): self
    {
        $this->allegroParentCategory = $allegroParentCategory;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getAdvertisement()
    {
        return $this->advertisement;
    }

    /**
     * @param mixed $advertisement
     * @return self
     */
    public function setAdvertisement($advertisement): self
    {
        $this->advertisement = $advertisement;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getAdvertisementPriceOptional()
    {
        return $this->advertisementPriceOptional;
    }

    /**
     * @param mixed $advertisementPriceOptional
     * @return self
     */
    public function setAdvertisementPriceOptional($advertisementPriceOptional): self
    {
        $this->advertisementPriceOptional = $advertisementPriceOptional;
        return $this;

    }

    /**
     * @return
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return  self
     */
    public function setParameters($parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }



}
