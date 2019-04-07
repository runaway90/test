<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroserviceOperationLogsRepository")
 */
class MicroserviceOperationLogs
{
    const APP_LEVEL_ALLEGRO_CLIENT = 'allegro_client';
    const APP_LEVEL_MS_API = 'ms_api';
    const APP_LEVEL_DEFAULT = 'default';

    const OP_NAME_REQUEST = 'request';
    const OP_NAME_SUCCESS_RESPONSE = 'response_success';
    const OP_NAME_ERROR_RESPONSE = 'response_error';
    const OP_NAME_DB_CHANGES = 'db_change';
    const OP_NAME_AUTHORIZATION = 'authorization';

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
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=25)
     * constant variables: APP_LEVEL_ALLEGRO_CLIENT, APP_LEVEL_MICROSERVICE, APP_LEVEL_UNKNOWING
     */
    private $appLevel;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\Column(type="boolean")
     */
    private $alert;

    /**
     * @ORM\ManyToOne(targetEntity="AllegroUserAccounts", inversedBy="logs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $allegroAccount;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAppLevel(): ?string
    {
        return $this->appLevel;
    }

    public function setAppLevel(string $appLevel): self
    {
        $this->appLevel = $appLevel;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getAlert(): ?bool
    {
        return $this->alert;
    }

    public function setAlert(bool $alert): self
    {
        $this->alert = $alert;

        return $this;
    }

    public function getAllegroAccount()
    {
        return $this->allegroAccount;
    }

    public function setAllegroAccount($allegroAccount):self
    {
        $this->allegroAccount = $allegroAccount;
        return $this;
    }

}
