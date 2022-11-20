<?php

namespace Iabdullahbeker\EfatoraSdk\Utils;


class InvoiceLines
{

    private $items = [];
    public $total = 0;
    public $total_discount = 0;
    public $total_tax = 0;

    // public function __construct(EfatoraProduct $product)
    // {
    //     array_push($this->products,$product);
    // }

    public function toArray(): array
    {
        return $this->items;
    }

    public function add(array $item){
        $this->total += ($item['quantity'] * $item['unitValue']['amountEGP']);
        // $this->total_discount = 0;
        // $tax = ($item->item_quantity * $item->item_price_each * 0.14);
        $this->total_tax += $item['taxableItems'][0]['amount'];

        array_push($this->items,$item);
    }
}
