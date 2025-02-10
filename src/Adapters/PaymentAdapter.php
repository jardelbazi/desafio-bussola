<?php

namespace App\Adapters;

use App\DTO\PaymentDTO;

class PaymentAdapter extends PaymentDTO
{
    /**
     * @param array $data 
     */
    public function __construct(
        private array $data
    ) {
        parent::__construct(
            method: $data['method'],
            installment: $data['installment'] ?? 1,
            cardHolder: $data['cardHolder'] ?? null,
            cardNumber: $data['cardNumber'] ?? null,
            expiryDate: $data['expiryDate'] ?? null,
            cvv: $data['cvv'] ?? null,
        );
    }
}
