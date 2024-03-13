<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[Groups('account')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('account')]
    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[Groups('account')]
    #[ORM\Column(length: 255)]
    private ?string $currency = null;

    #[Groups('client')]
    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\Column]
    #[Groups('account')]
    private ?bool $active = null;

    #[Groups('account')]
    #[ORM\Column(nullable: true)]
    private ?float $coin = null;

    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'receiver')]
    private Collection $receivers;

    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'sender')]
    private Collection $senders;

    public function __construct()
    {
        $this->receivers = new ArrayCollection();
        $this->senders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getCoin(): ?float
    {
        return $this->coin;
    }

    public function setCoin(?float $coin): static
    {
        $this->coin = $coin;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getReceivers(): Collection
    {
        return $this->receivers;
    }

    public function addReceiver(Transaction $receiver): static
    {
        if (!$this->receivers->contains($receiver)) {
            $this->receivers->add($receiver);
            $receiver->setReceiver($this);
        }

        return $this;
    }

    public function removeReceiver(Transaction $receiver): static
    {
        if ($this->receivers->removeElement($receiver)) {
            // set the owning side to null (unless already changed)
            if ($receiver->getReceiver() === $this) {
                $receiver->setReceiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getSenders(): Collection
    {
        return $this->senders;
    }

    public function addSender(Transaction $sender): static
    {
        if (!$this->senders->contains($sender)) {
            $this->senders->add($sender);
            $sender->setSender($this);
        }

        return $this;
    }

    public function removeSender(Transaction $sender): static
    {
        if ($this->senders->removeElement($sender)) {
            // set the owning side to null (unless already changed)
            if ($sender->getSender() === $this) {
                $sender->setSender(null);
            }
        }

        return $this;
    }
}
