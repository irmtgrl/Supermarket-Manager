<?php

namespace App\Entity;

use App\Repository\CustomerOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerOrderRepository::class)]
class CustomerOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $totalPrice = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, CustomerOrderItem>
     */
    #[ORM\OneToMany(targetEntity: CustomerOrderItem::class, mappedBy: 'customerOrder')]
    private Collection $orderedItems;

    public function __construct()
    {
        $this->orderedItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

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

    /**
     * @return Collection<int, CustomerOrderItem>
     */
    public function getOrderedItems(): Collection
    {
        return $this->orderedItems;
    }

    public function addOrderedItem(CustomerOrderItem $orderedItem): static
    {
        if (!$this->orderedItems->contains($orderedItem)) {
            $this->orderedItems->add($orderedItem);
            $orderedItem->setCustomerOrder($this);
        }

        return $this;
    }

    public function removeOrderedItem(CustomerOrderItem $orderedItem): static
    {
        if ($this->orderedItems->removeElement($orderedItem)) {
            // set the owning side to null (unless already changed)
            if ($orderedItem->getCustomerOrder() === $this) {
                $orderedItem->setCustomerOrder(null);
            }
        }

        return $this;
    }
}
