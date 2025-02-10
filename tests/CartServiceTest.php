<?php

namespace Tests;

use App\DTO\ItemDTO;
use App\Services\CartService;
use PHPUnit\Framework\TestCase;

class CartServiceTest extends TestCase
{
    public function testCanAddItemsToCart()
    {
        $cart = new CartService();

        $cart->addItem([
            'name' => 'Produto A',
            'price' => 100.00,
            'quantity' => 2
        ]);
        $cart->addItem([
            'name' => 'Produto B',
            'price' => 50.00,
            'quantity' => 1
        ]);

        $items = $cart->getItems();

        $this->assertNotEmpty($items);

        foreach ($items as $item) {
            $this->assertInstanceOf(ItemDTO::class, $item);
        }

        $this->assertEquals('Produto A', $items[0]->getName());
        $this->assertEquals(100.00, $items[0]->getPrice());
        $this->assertEquals(2, $items[0]->getQuantity());

        $this->assertEquals('Produto B', $items[1]->getName());
        $this->assertEquals(50.00, $items[1]->getPrice());
        $this->assertEquals(1, $items[1]->getQuantity());

        $this->assertCount(2, $items);

        $this->assertEquals(250.00, $cart->getTotal());
    }
}
