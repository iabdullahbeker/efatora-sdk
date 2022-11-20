<?php

namespace Iabdullahbeker\EfatoraSdk\Utils;


class EfatoraProducts
{

    private $products = ["items" => []];

    // public function __construct(EfatoraProduct $product)
    // {
    //     array_push($this->products,$product);
    // }

    public function toArray(): array
    {
        return $this->products;
    }

    public function add(array $product){
        $product_array = [
            "codeType" => $product['codeType'],
            "parentCode" => $product['parentCode'] ?? "99999999",
            "itemCode" => "EG-376877448-" . $product['itemCode'],
            "codeName" => $product['codeName'],
            "codeNameAr" => $product['codeNameAr'],
            "activeFrom" => $product['activeFrom'] ?? date('Y-m-d\TH:i:s'),
            // "activeTo"=>"2022-08-21T00:00:00.000",
            "description" => $product['description'],
            "descriptionAr" => $product['descriptionAr'],
            "requestReason" => $product['requestReason']
        ];
        array_push($this->products['items'],$product_array);
    }
}
