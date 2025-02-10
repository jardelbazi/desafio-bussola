<?php

namespace App\Services;

use App\Adapters\ItemAdapter;
use App\Adapters\PaymentAdapter;
use App\Exceptions\InvalidPaymentMethodException;
use App\Exceptions\PaymentMethodNotSetException;
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
     * @throws PaymentMethodNotSetException 
     */
    public function checkout(): float
    {
        if (is_null($this->paymentStrategy)) {
            throw new PaymentMethodNotSetException('Não foi setado o método de pagamento');
        }

        return $this->paymentStrategy->calculateTotal($this->getSubtotal());
    }

    /**
     * @param PaymentAdapter $paymentAdapter 
     * @return void 
     * @throws InvalidPaymentMethodException 
     */
    public function setPaymentMethod(PaymentAdapter $paymentAdapter): void
    {
        $method = $paymentAdapter->getMethod();
        $strategyClass = $this->getPaymentStrategyClass($method);

        if (!class_exists($strategyClass)) {
            throw new InvalidPaymentMethodException(
                sprintf("O método de pagamento '%s' é inválido ou a classe de estratégia não foi encontrada.", $method)
            );
        }

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
