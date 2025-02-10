<?php

namespace App\DTO;

abstract class PaymentDTO
{
    /**
     * @param string $method 
     * @param int $installment 
     * @param null|string $cardHolder 
     * @param null|int $cardNumber 
     * @param null|string $expiryDate 
     * @param null|int $cvv 
     */
    public function __construct(
        protected string $method,
        protected int $installment,
        protected ?string $cardHolder = null,
        protected ?int $cardNumber = null,
        protected ?string $expiryDate = null,
        protected ?int $cvv = null,
    ) {}

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return int
     */
    public function getInstallment(): int
    {
        return $this->installment;
    }

    /**
     * @return string|null
     */
    public function getCardHolder(): ?string
    {
        return $this->cardHolder;
    }

    /**
     * @return int|null
     */
    public function getCardNumber(): ?int
    {
        return $this->cardNumber;
    }

    /**
     * @return string|null
     */
    public function getExpiryDate(): ?string
    {
        return $this->expiryDate;
    }

    /**
     * @return int|null
     */
    public function getCVV(): ?int
    {
        return $this->cvv;
    }
}
