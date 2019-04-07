<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserAllegroAccountsRepository")
 *
 */
class AllegroUserAccounts
{
    const LOG_OP_REGISTER_NEW_ACC = 'register_new_account';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $sellerId;

    /**
     * @ORM\OneToOne(targetEntity="AllegroActivateDevice")
     */
    private $activeAllegroDevice;

    /**
     * @ORM\OneToOne(targetEntity="AllegroTokens")
     */
    private $accessAllegroToken;

    /**
     * @ORM\OneToMany(targetEntity="MicroserviceToken", mappedBy="msIdToken")
     */
    protected $msIdToken;

    /**
     * @ORM\OneToMany(targetEntity="MicroserviceOperationLogs", mappedBy="logs", cascade={"persist","remove"})
     */
    protected $logs;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroserviceApplication", inversedBy="accounts")
     */
    private $application;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AllegroPhoto", mappedBy="allegroUser", cascade={"persist","remove"})
     */
    private $allegroPhotos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AllegroEvent", mappedBy="userAccount")
     */
    private $allegroEvents;


    public function __construct()
    {
        $this->msIdToken = new ArrayCollection();
        $this->logs = new ArrayCollection();
        $this->allegroPhotos = new ArrayCollection();
        $this->allegroEvents = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getMsIdToken()
    {
        return $this->msIdToken;
    }

    public function setMsIdToken($msIdToken): self
    {
        $this->msIdToken = $msIdToken;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     */
    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getActiveAllegroDevice()
    {
        return $this->activeAllegroDevice;
    }

    /**
     * @param AllegroActivateDevice $activeAllegroDevice
     * @return AllegroUserAccounts
     */
    public function setActiveAllegroDevice(AllegroActivateDevice $activeAllegroDevice): self
    {
        $this->activeAllegroDevice = $activeAllegroDevice;
        return $this;

    }

    public function getAccessAllegroToken()
    {
        return $this->accessAllegroToken;
    }

    /**
     * @param AllegroTokens $accessAllegroToken
     * @return AllegroUserAccounts
     */
    public function setAccessAllegroToken(AllegroTokens $accessAllegroToken): self
    {
        $this->accessAllegroToken = $accessAllegroToken;
        return $this;

    }

    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param MicroserviceOperationLogs $logs
     * @return AllegroUserAccounts
     */
    public function setLogs(MicroserviceOperationLogs $logs): self
    {
        $this->logs = $logs;
        return $this;
    }

    public function getApplication(): ?MicroserviceApplication
    {
        return $this->application;
    }

    public function setApplication(MicroserviceApplication $application): self
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @return self
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return self
     */
    public function setLogin($login): self
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return self
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return self
     */
    public function setPassword($password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getSellerId()
    {
        return $this->sellerId;
    }

    /**
     * @param string $sellerId
     * @return self
     */
    public function setSellerId($sellerId): self
    {
        $this->sellerId = $sellerId;
        return $this;
    }

    /**
     * @return Collection|AllegroPhoto[]
     */
    public function getAllegroPhotos(): Collection
    {
        return $this->allegroPhotos;
    }

    public function addAllegroPhoto(AllegroPhoto $allegroPhoto): self
    {
        if (!$this->allegroPhotos->contains($allegroPhoto)) {
            $this->allegroPhotos[] = $allegroPhoto;
            $allegroPhoto->setAllegroUser($this);
        }

        return $this;
    }

    public function removeAllegroPhoto(AllegroPhoto $allegroPhoto): self
    {
        if ($this->allegroPhotos->contains($allegroPhoto)) {
            $this->allegroPhotos->removeElement($allegroPhoto);
            // set the owning side to null (unless already changed)
            if ($allegroPhoto->getAllegroUser() === $this) {
                $allegroPhoto->setAllegroUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AllegroEvent[]
     */
    public function getAllegroEvents(): Collection
    {
        return $this->allegroEvents;
    }

    public function addAllegroEvent(AllegroEvent $allegroEvent): self
    {
        if (!$this->allegroEvents->contains($allegroEvent)) {
            $this->allegroEvents[] = $allegroEvent;
            $allegroEvent->setUserAccount($this);
        }

        return $this;
    }

    public function removeAllegroEvent(AllegroEvent $allegroEvent): self
    {
        if ($this->allegroEvents->contains($allegroEvent)) {
            $this->allegroEvents->removeElement($allegroEvent);
            // set the owning side to null (unless already changed)
            if ($allegroEvent->getUserAccount() === $this) {
                $allegroEvent->setUserAccount(null);
            }
        }

        return $this;
    }

}
