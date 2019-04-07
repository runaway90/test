<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroserviceApplicationRepository")
 */
class MicroserviceApplication implements UserInterface
{
    const LOG_OP_CREATE_SECRET_PASS = 'create_secret_pass';
    const LOG_OP_SEND_REQUEST_TO_CORE = 'send_request_to_core';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uri;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $appId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $appSecret;
    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AllegroUserAccounts", mappedBy="application", cascade={"persist", "remove"})
     */
    private $accounts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroserviceApplicationIps", mappedBy="application", cascade={"persist", "remove"})
     */
    private $applicationIps;

    public function __construct()
    {
        $this->roles[] = 'ROLE_API_APP';
        $this->accounts = new ArrayCollection();
        $this->applicationIps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function setAppId(string $appId): self
    {
        $this->appId = $appId;

        return $this;
    }

    public function getAppSecret(): ?string
    {
        return $this->appSecret;
    }

    public function setAppSecret(string $appSecret): self
    {
        $this->appSecret = $appSecret;

        return $this;
    }

    /**
     * @return Collection|AllegroUserAccounts[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(AllegroUserAccounts $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setApplication($this);
        }

        return $this;
    }

    public function removeAccount(AllegroUserAccounts $account): self
    {
        if ($this->accounts->contains($account)) {
            $this->accounts->removeElement($account);
            // set the owning side to null (unless already changed)
            if ($account->getApplication() === $this) {
                $account->setApplication(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|MicroserviceApplicationIps[]
     */
    public function getApplicationIps(): Collection
    {
        return $this->applicationIps;
    }

    public function addApplicationIp(MicroserviceApplicationIps $applicationIp): self
    {
        if (!$this->applicationIps->contains($applicationIp)) {
            $this->applicationIps[] = $applicationIp;
            $applicationIp->setApplication($this);
        }

        return $this;
    }

    public function removeApplicationIp(MicroserviceApplicationIps $applicationIp): self
    {
        if ($this->applicationIps->contains($applicationIp)) {
            $this->applicationIps->removeElement($applicationIp);
            // set the owning side to null (unless already changed)
            if ($applicationIp->getApplication() === $this) {
                $applicationIp->setApplication(null);
            }
        }

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_API_APP
        $roles[] = 'ROLE_API_APP';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return self
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }


    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->appId;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
