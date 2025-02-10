<?php

namespace App\Adapters;

use App\DTO\ItemDTO;

class ItemAdapter extends ItemDTO
{
    /**
     * @param array $data 
     */
    public function __construct(
        private array $data
    ) {
        parent::__construct(
            name: $data['name'],
            price: $data['price'],
            quantity: $data['quantity'],
        );
    }
}
