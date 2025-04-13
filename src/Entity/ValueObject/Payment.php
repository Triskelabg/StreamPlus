<?php
namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Payment
{
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $cardNumber = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $expirationDate = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $cvv = null;

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(?string $cardNumber): void
    {
        $this->cardNumber = $cardNumber;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?string $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(?string $cvv): void
    {
        $this->cvv = $cvv;
    }
}
