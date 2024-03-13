<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $value = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'receivers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $receiver = null;

    #[ORM\ManyToOne(inversedBy: 'senders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $sender = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getReceiver(): ?Account
    {
        return $this->receiver;
    }

    public function setReceiver(?Account $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getSender(): ?Account
    {
        return $this->sender;
    }

    public function setSender(?Account $sender): static
    {
        $this->sender = $sender;

        return $this;
    }
}
