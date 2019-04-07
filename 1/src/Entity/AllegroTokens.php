<?php

namespace App\Entity;

use App\Entity\Traits\Date\CreatedAt;
use App\Entity\Traits\Date\FinishTo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllegroAccessTokensRepository")
 */
class AllegroTokens
{
    const LOG_OP_CREATE_TOKEN = 'create_token';
    const LOG_OP_REFRESH_TOKEN = 'refresh_token';

    use CreatedAt;
    use FinishTo;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1255)
     */
    private $accessToken;

    /**
     * @ORM\Column(type="string", length=1255)
     */
    private $refreshToken;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tokenType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $scope;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jti;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $tokenKind;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $redirectUri;

    //mappedBy
    /**
     * @ORM\OneToOne(targetEntity="AllegroUserAccounts", inversedBy="accessAllegroToken")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    public function setTokenType(string $tokenType): self
    {
        $this->tokenType = $tokenType;
        return $this;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function setScope(string $scope): self
    {
        $this->scope = $scope;
        return $this;
    }

    public function getJti(): ?string
    {
        return $this->jti;
    }

    public function setJti(string $jti): self
    {
        $this->jti = $jti;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param AllegroUserAccounts $user
     * @return AllegroTokens
     */
    public function setUser(AllegroUserAccounts $user)
    {
        $this->user = $user;
        return $this;

    }

    /**
     * @return string
     */
    public function getTokenKind()
    {
        return $this->tokenKind;
    }

    /**
     * @param string $tokenKind
     * @return self
     */
    public function setTokenKind(string $tokenKind): self
    {
        $this->tokenKind = $tokenKind;
        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param string $redirectUri
     * @return self
     */
    public function setRedirectUri($redirectUri): self
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }


}
