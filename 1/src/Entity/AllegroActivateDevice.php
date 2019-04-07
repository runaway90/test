<?php

namespace App\Entity;

use App\Entity\Traits\Date\CreatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActiveAllegroDeviceRepository")
 */
class AllegroActivateDevice
{
    const LOG_OP_CREATE_DEVICE_CODE = 'create_device_code';
    const LOG_OP_CHANGE_DEVICE_STATUS = 'change_status_device';

    use CreatedAt;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deviceCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $varificationUriComplited; // TODO verification Completed

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $requestIntervalTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attention;

    /**
     * @ORM\OneToOne(targetEntity="AllegroUserAccounts", inversedBy="activeAllegroDevice")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceCode(): ?string
    {
        return $this->deviceCode;
    }

    public function setDeviceCode(string $deviceCode): self
    {
        $this->deviceCode = $deviceCode;
        return $this;
    }

    public function getUserCode(): ?string
    {
        return $this->userCode;
    }

    public function setUserCode(string $userCode): self
    {
        $this->userCode = $userCode;
        return $this;
    }

    public function getVarificationUriComplited(): ?string
    {
        return $this->varificationUriComplited;
    }

    public function setVarificationUriComplited(string $varificationUriComplited): self
    {
        $this->varificationUriComplited = $varificationUriComplited;
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

    public function getRequestIntervalTime(): ?\DateTimeInterface
    {
        return $this->requestIntervalTime;
    }

    public function setRequestIntervalTime(\DateTimeInterface $requestIntervalTime): self
    {
        $this->requestIntervalTime = $requestIntervalTime;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getAttention(): ?string
    {
        return $this->attention;
    }

    public function setAttention(?string $attention): self
    {
        $this->attention = $attention;
        return $this;
    }

    /**
     * @return AllegroUserAccounts
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param AllegroUserAccounts $user
     * @return AllegroActivateDevice
     */
    public function setUser(AllegroUserAccounts $user)
    {
        $this->user = $user;
        return $this;
    }
}
