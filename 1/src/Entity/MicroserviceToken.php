<?php

namespace App\Entity;

use App\Entity\Traits\Date\CreatedAt;
use App\Entity\Traits\Date\FinishTo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroserviceTokenRepository")
 */
class MicroserviceToken
{
    use CreatedAt;
    use FinishTo;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $priority;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accessToken;

    /**
     * @ORM\ManyToOne(targetEntity="AllegroUserAccounts", inversedBy="userAllegro", cascade="persist")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $userAllegro;

    /**
     * @return mixed
     */
    public function getUserAllegro()
    {
        return $this->userAllegro;
    }

    /**
     */
    public function setUserAllegro($userAllegro): self
    {
        $this->userAllegro = $userAllegro;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;
        return $this;
    }

}
