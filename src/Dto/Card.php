<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Card {
    #[Assert\NotBlank]
    #[Assert\CardScheme(schemes: ['VISA', 'MASTERCARD'])]
    private int $number;

    #[Assert\NotBlank]
    private int $year;

    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 12)]
    private int $month;

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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;

        return $this;
    }
}