<?php

namespace App\Services;

use App\Adapters\ItemAdapter;

class CartService
{
    /**
     * @var ItemDTO[]
     */
    private array $items = [];

    /**
     * @param array $itemData
     * @return void
     */
    public function addItem(array $itemData): void
    {
        $this->items[] = new ItemAdapter($itemData);
    }

    /**
     * @return ItemDTO[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return float 
     */
    public function getTotal(): float
    {
        $subtotal = 0;

        foreach ($this->items as $item) {
            $subtotal += $item->getPrice() * $item->getQuantity();
        }

        return $subtotal;
    }
}
