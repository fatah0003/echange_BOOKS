<?php

namespace App\Entity;

use App\Enum\ExchangeEnum;
use App\Repository\ExchangeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRepository::class)]
class Exchange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'exchangeRequest')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userRequester = null;

    #[ORM\ManyToOne(inversedBy: 'exchangeReceive')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userReceiver = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Books $bookOne = null;

    #[ORM\ManyToOne]
    private ?Books $bookTwo = null;

    #[ORM\Column(enumType: ExchangeEnum::class)]
    private ?ExchangeEnum $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $acceptedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserRequester(): ?User
    {
        return $this->userRequester;
    }

    public function setUserRequester(?User $userRequester): static
    {
        $this->userRequester = $userRequester;

        return $this;
    }

    public function getUserReceiver(): ?User
    {
        return $this->userReceiver;
    }

    public function setUserReceiver(?User $userReceiver): static
    {
        $this->userReceiver = $userReceiver;

        return $this;
    }

    public function getBookOne(): ?Books
    {
        return $this->bookOne;
    }

    public function setBookOne(?Books $bookOne): static
    {
        $this->bookOne = $bookOne;

        return $this;
    }

    public function getBookTwo(): ?Books
    {
        return $this->bookTwo;
    }

    public function setBookTwo(?Books $bookTwo): static
    {
        $this->bookTwo = $bookTwo;

        return $this;
    }

    public function getStatus(): ?ExchangeEnum
    {
        return $this->status;
    }

    public function setStatus(ExchangeEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAcceptedAt(): ?\DateTimeImmutable
    {
        return $this->acceptedAt;
    }

    public function setAcceptedAt(?\DateTimeImmutable $acceptedAt): static
    {
        $this->acceptedAt = $acceptedAt;

        return $this;
    }
}
