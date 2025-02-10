<?php

namespace App\Services;

use App\Adapters\ItemAdapter;
use App\Adapters\PaymentAdapter;
use App\Strategy\PaymentStrategy;

class CartService
{
    /**
     * @var ItemDTO[]
     */
    private array $items = [];

    /**
     * @var PaymentStrategy
     */
    private ?PaymentStrategy $paymentStrategy = null;

    /**
     * @var float
     */
    private float $subtotal = 0;

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
    public function getSubtotal(): float
    {
        foreach ($this->items as $item) {
            $this->subtotal += $item->getPrice() * $item->getQuantity();
        }

        return $this->subtotal;
    }

    /**
     * @return float 
     */
    public function checkout(): float
    {
        return $this->paymentStrategy->calculateTotal($this->getSubtotal());
    }

    /**
     * @param PaymentAdapter $paymentAdapter 
     * @return void 
     */
    public function setPaymentMethod(PaymentAdapter $paymentAdapter): void
    {
        $strategyClass = $this->getPaymentStrategyClass($paymentAdapter->getMethod());
        $this->paymentStrategy = new $strategyClass($paymentAdapter->getInstallment());
    }

    /**
     * @param string $method
     * @return string
     */
    private function getPaymentStrategyClass(string $method): string
    {
        return "App\\Strategy\\" . ucfirst($method) . "Payment";
    }
}
