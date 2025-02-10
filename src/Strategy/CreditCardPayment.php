<?php

namespace App\Strategy;

class CreditCardPayment implements PaymentStrategy
{
    /**
     * @param int $installments 
     */
    public function __construct(
        private int $installments
    ) {}

    /**
     * {@inheritdoc}
     */
    public function calculateTotal(float $amount): float
    {
        if ($this->installments == 1) {
            return $amount;
        }

        $interestRate = 0.01;
        return round($amount * pow(1 + $interestRate, $this->installments), 2);
    }
}
