<?php

namespace Tests;

use App\Adapters\PaymentAdapter;
use App\DTO\ItemDTO;
use App\Exceptions\InvalidPaymentMethodException;
use App\Exceptions\PaymentMethodNotSetException;
use App\Services\CartService;
use PHPUnit\Framework\TestCase;

class CartServiceTest extends TestCase
{
    public function testCanAddItemsToCart()
    {
        $cart = new CartService();

        $cart->addItem(['name' => 'Produto A', 'price' => 100.00, 'quantity' => 2]);
        $cart->addItem(['name' => 'Produto B', 'price' => 50.00, 'quantity' => 1]);

        $items = $cart->getItems();

        $this->assertNotEmpty($items, 'O carrinho está vazio');

        foreach ($items as $item) {
            $this->assertInstanceOf(ItemDTO::class, $item, 'O item não é uma instância de ItemDTO');
        }

        $this->assertEquals('Produto A', $items[0]->getName(), 'Nome do Produto A está incorreto');
        $this->assertEquals(100.00, $items[0]->getPrice(), 'Preço do Produto A está incorreto');
        $this->assertEquals(2, $items[0]->getQuantity(), 'Quantidade do Produto A está incorreta');

        $this->assertEquals('Produto B', $items[1]->getName(), 'Nome do Produto B está incorreto');
        $this->assertEquals(50.00, $items[1]->getPrice(), 'Preço do Produto B está incorreto');
        $this->assertEquals(1, $items[1]->getQuantity(), 'Quantidade do Produto B está incorreta');

        $this->assertCount(2, $items, 'Número de itens no carrinho está incorreto');

        $this->assertEquals(250.00, $cart->getSubtotal(), 'Subtotal está incorreto');
    }

    public function testTotalWithPixPayment()
    {
        $cart = new CartService();

        $cart->addItem(['name' => 'Produto A', 'price' => 200.00, 'quantity' => 1]);
        $cart->addItem(['name' => 'Produto B', 'price' => 10.00, 'quantity' => 1]);
        $cart->addItem(['name' => 'Produto C', 'price' => 30.00, 'quantity' => 4]);

        $items = $cart->getItems();

        $this->assertNotEmpty($items, 'O carrinho está vazio');

        foreach ($items as $item) {
            $this->assertInstanceOf(ItemDTO::class, $item, 'O item não é uma instância de ItemDTO');
        }

        $paymentData = new PaymentAdapter(['method' => 'pix']);
        $cart->setPaymentMethod($paymentData);

        $this->assertInstanceOf(PaymentAdapter::class, $paymentData, 'Os dados de pagamento não é uma instância de PaymentAdapter');
        $this->assertEquals('pix', $paymentData->getMethod(), 'O método de pagamento informado não é "pix"');

        $this->assertEquals(297.00, $cart->checkout(), 'Total com pagamento via Pix está incorreto');
    }

    public function testTotalWithCreditCardPaymentOneInstallment()
    {
        $cart = new CartService();

        $cart->addItem(['name' => 'Produto D', 'price' => 350.00, 'quantity' => 1]);

        $items = $cart->getItems();

        $this->assertNotEmpty($items, 'O carrinho está vazio');

        foreach ($items as $item) {
            $this->assertInstanceOf(ItemDTO::class, $item, 'O item não é uma instância de ItemDTO');
        }

        $paymentData = new PaymentAdapter([
            'method' => 'creditCard',
            'cardHolder' => 'Evandro Mesquita',
            'cardNumber' => 1234567890123456,
            'expiryDate' => '12/2029',
            'cvv' => 456
        ]);

        $cart->setPaymentMethod($paymentData);

        $this->assertInstanceOf(PaymentAdapter::class, $paymentData, 'Os dados de pagamento não é uma instância de PaymentAdapter');
        $this->assertEquals('creditCard', $paymentData->getMethod(), 'O método de pagamento informado não é "pix"');

        $this->assertEquals(350.00, $cart->checkout(), 'Total com pagamento via Cartão de Crédito está incorreto');
    }

    public function testTotalWithCreditCardPaymentThreeInstallments()
    {
        $cart = new CartService();

        $cart->addItem(['name' => 'Produto D', 'price' => 350.00, 'quantity' => 5]);

        $items = $cart->getItems();

        $this->assertNotEmpty($items, 'O carrinho está vazio');

        foreach ($items as $item) {
            $this->assertInstanceOf(ItemDTO::class, $item, 'O item não é uma instância de ItemDTO');
        }

        $paymentData = new PaymentAdapter([
            'method' => 'creditCard',
            'installment' => 3,
            'cardHolder' => 'Paulo Soares',
            'cardNumber' => 1234567890123456,
            'expiryDate' => '12/2025',
            'cvv' => 123
        ]);

        $cart->setPaymentMethod($paymentData);

        $this->assertInstanceOf(PaymentAdapter::class, $paymentData, 'Os dados de pagamento não é uma instância de PaymentAdapter');
        $this->assertEquals('creditCard', $paymentData->getMethod(), 'O método de pagamento informado não é "pix"');
        $this->assertEquals(3, $paymentData->getInstallment(), 'A quantidade de parcelas informada deve ser igual a 3');

        $interestRate = 0.01;
        $expectedTotal = round(1750 * pow(1 + $interestRate, 3), 2);

        $this->assertEquals($expectedTotal, $cart->checkout(), 'Total com pagamento via Cartão de Crédito em 3x está incorreto');
    }

    public function testCheckoutThrowsExceptionWhenPaymentMethodNotSet()
    {
        $this->expectException(PaymentMethodNotSetException::class);
        $this->expectExceptionMessage('Não foi setado o método de pagamento');

        $cart = new CartService();
        $cart->addItem(['name' => 'Produto A', 'price' => 100.00, 'quantity' => 2]);

        $cart->checkout();
    }

    public function testSetPaymentMethodThrowsExceptionForInvalidMethod()
    {
        $this->expectException(InvalidPaymentMethodException::class);
        $this->expectExceptionMessage("O método de pagamento 'fakeMethod' é inválido ou a classe de estratégia não foi encontrada.");

        $cart = new CartService();
        $paymentData = new PaymentAdapter(['method' => 'fakeMethod']); // Método inválido

        $cart->setPaymentMethod($paymentData);
    }
}
