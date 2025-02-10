<?php

namespace App\Strategy;

interface PaymentStrategy
{
    /**
     * @param float $amount 
     * @return float 
     */
    public function calculateTotal(float $amount): float;
}
