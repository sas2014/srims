<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
#[ORM\Table(name: '`token`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_TOKEN', fields: ['token'])]
class Token
{
    #[ORM\Id]
    #[ORM\Column(length: 100, nullable: false)]
    private string $token;


    #[ORM\Column(name: "expiration_date", type: "datetime", nullable: false)]
    private \DateTimeInterface  $expirationDate;

    #[ORM\ManyToOne(inversedBy: 'tokens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpirationDate(): \DateTimeInterface
    {
        return $this->expirationDate;
    }

    /**
     * @param \DateTimeInterface $expirationDate
     * @return static
     */
    public function setExpirationDate(\DateTimeInterface $expirationDate): static
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
