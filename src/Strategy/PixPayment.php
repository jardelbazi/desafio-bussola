<?php

namespace App\Strategy;

class PixPayment implements PaymentStrategy
{
    /**
     * {@inheritdoc}
     */
    public function calculateTotal(float $amount): float
    {
        return $amount * 0.9;
    }
}
