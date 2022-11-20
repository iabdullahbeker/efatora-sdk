<?php

namespace Iabdullahbeker\EfatoraSdk\Utils;


class EfatoraReceiptItem
{

    private $id;
    private $description;
    private $item_type;
    private $item_code;
    private $item_quantity;
    private $item_price_each;

    public function __construct(array $args)
    {
        $this->id = $args['id'];
        $this->description = $args['description'];
        $this->item_type = $args['item_type'];
        $this->item_code = $args['item_code'];
        $this->item_quantity = $args['item_quantity'];
        $this->item_price_each = $args['item_price_each'];
    }

    public function toArray(): array
    {
        $tax = ($this->item_quantity * $this->item_price_each * 0.14);
        return [
            "description" => $this->description,
            "itemType" => $this->item_type ?? "EGS",
            "itemCode" => (string)$this->item_code,
            "unitType" => "EA",
            "quantity" => (float)$this->item_quantity,
            "internalCode" => (string)$this->id,
            "salesTotal" => $this->item_quantity * $this->item_price_each, // piece price
            "total" => $this->item_quantity * $this->item_price_each + $tax , // piece price * quantity
            "valueDifference" => 0,
            "totalTaxableFees" => 0,
            "netTotal" => $this->item_quantity * $this->item_price_each,
            "itemsDiscount" => 0,
            "unitValue" => [
                "currencySold" => "EGP",
                "amountEGP" => $this->item_price_each
            ],
            "discount" => [
                "rate" => 0,
                "amount" => 0
            ],
            "taxableItems" => [
                [
                    "taxType" => "T1",
                    "amount" => round($tax,5),
                    "subType" => "V009",
                    "rate" => 14
                ]
            ]
        ];
    }
}
