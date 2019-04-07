<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="microservice_applications_ips")
 * @ORM\Entity(repositoryClass="")
 */
class MicroserviceApplicationIps
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="binary", length=16)
     */
    private $ip;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroserviceApplication", inversedBy="applicationIps")
     */
    private $application;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

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
}
