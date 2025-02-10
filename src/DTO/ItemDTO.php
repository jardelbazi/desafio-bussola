<?php

namespace App\DTO;

abstract class ItemDTO
{
    /**
     * @param string $name 
     * @param float $price 
     * @param int $quantity 
     */
    public function __construct(
        protected string $name,
        protected float $price,
        protected int $quantity,
    ) {}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
