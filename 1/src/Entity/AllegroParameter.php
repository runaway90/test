<?php

namespace App\Entity;

use App\Entity\Traits\Allegro\Name;
use App\Entity\Traits\Date\CreatedAt;
use App\Entity\Traits\Allegro\UUID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllegroParameterRepository")
 */
class AllegroParameter
{
    use CreatedAt;
    use Name;
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
     * @ORM\Column(type="boolean")
     */
    private $required;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unit;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $variantAllowed;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $variantEqual;

    /**
     * @ORM\Column(type="object")
     */
    private $restrictions;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $dictionary;

//
//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\AllegroParameterDictionary", mappedBy="allegroParameter", cascade={"persist","remove"})
//     */
//    private $parametersDictionary;
//
//    public function __construct()
//    {
//        $this->parametersDictionary = new ArrayCollection();
//    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AllegroCategories", inversedBy="parameters", cascade="persist")
     */
    private $allegroCategory;


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

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param mixed $unit
     * @return self
     */
    public function setUnit($unit): self
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getVariantAllowed()
    {
        return $this->variantAllowed;
    }

    /**
     * @param boolean $variantAllowed
     * @return self
     */
    public function setVariantAllowed($variantAllowed): self
    {
        $this->variantAllowed = $variantAllowed;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getVariantEqual()
    {
        return $this->variantEqual;
    }

    /**
     * @param boolean $variantEqual
     * @return self
     */
    public function setVariantEqual($variantEqual): self
    {
        $this->variantEqual = $variantEqual;
        return $this;
    }

    /**
     * @return object
     */
    public function getRestrictions()
    {
        return $this->restrictions;
    }

    /**
     * @return self
     */
    public function setRestrictions($restrictions): self
    {
        $this->restrictions = $restrictions;
        return $this;
    }

//    /**
//     * @return Collection|AllegroParameterDictionary[]
//     */
//    public function getParametersDictionary(): Collection
//    {
//        return $this->parametersDictionary;
//    }
//
//    public function addParametersDictionary(AllegroParameterDictionary $parametersDictionary): self
//    {
//        if (!$this->parametersDictionary->contains($parametersDictionary)) {
//            $this->parametersDictionary[] = $parametersDictionary;
//            $parametersDictionary->setAllegroParameter($this);
//        }
//
//        return $this;
//    }
//
//    public function removeParametersDictionary(AllegroParameterDictionary $parametersDictionary): self
//    {
//        if ($this->parametersDictionary->contains($parametersDictionary)) {
//            $this->parametersDictionary->removeElement($parametersDictionary);
//            // set the owning side to null (unless already changed)
//            if ($parametersDictionary->getAllegroParameter() === $this) {
//                $parametersDictionary->setAllegroParameter(null);
//            }
//        }
//
//        return $this;
//    }

    /**
     * @return string
     */
    public function getAllegroCategory()
    {
        return $this->allegroCategory;
    }

    /**
     * @param
     * @return self
     */
    public function setAllegroCategory($allegroCategory): self
    {
        $this->allegroCategory = $allegroCategory;
        return $this;
    }

    /**
     * @return self
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }

    /**
     * @param $dictionary
     * @return self
     */
    public function setDictionary($dictionary): self
    {
        $this->dictionary = $dictionary;
        return $this;
    }




}