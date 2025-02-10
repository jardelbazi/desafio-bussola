<?php

use App\Adapters\PaymentAdapter;
use App\Services\CartService;

include('vendor/autoload.php');

$cart = new CartService();

$cart->addItem(['name' => 'Produto A', 'price' => 100.00, 'quantity' => 2]);
$cart->addItem(['name' => 'Produto B', 'price' => 50.00, 'quantity' => 1]);

$cart->setPaymentMethod(new PaymentAdapter([
    'method' => 'creditCard',
    'cardHolder' => 'Evandro Mesquita',
    'cardNumber' => 1234567890123456,
    'expiryDate' => '12/2029',
    'cvv' => 456
]));

echo 'Total do Pedido: R$ ' . $cart->checkout() . PHP_EOL;
