<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Card {
    #[Assert\NotBlank]
    #[Assert\CardScheme(schemes: ['VISA', 'MASTERCARD'])]
    private int $number;

    #[Assert\NotBlank]
    #[Assert\Date]
    private string $date;

    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 999)]
    private string $cvv;

    #[Assert\NotBlank]
    private string $name;

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(string $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
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
}