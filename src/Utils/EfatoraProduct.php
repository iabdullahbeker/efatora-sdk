<?php

namespace Iabdullahbeker\EfatoraSdk\Utils;


class EfatoraProduct
{

    private $parentCode;
    private $productId;
    private $nameEn;
    private $nameAr;
    private $dateTime;
    private $descriptionEn;
    private $descriptionAr;
    private $requestReason;

    public function __construct(array $args)
    {
        $this->parentCode = $args['parentCode'];
        $this->productId = $args['productId'];
        $this->nameEn = $args['nameEn'];
        $this->nameAr = $args['nameAr'];
        $this->dateTime = $args['dateTime'];
        $this->descriptionEn = $args['descriptionEn'];
        $this->descriptionAr = $args['descriptionAr'];
        $this->requestReason = $args['requestReason'];
    }

    public function toArray(): array
    {
        return [
                    "codeType" => "EGS",
                    "parentCode" => $this->parentCode ?? "99999999",
                    "itemCode" => $this->productId,
                    "codeName" => $this->nameEn,
                    "codeNameAr" => $this->nameAr,
                    "activeFrom" => $this->date ?? date('Y-m-d\Th:m:s'),
                    // "activeTo"=>"2022-08-21T00:00:00.000",
                    "description" => $this->descriptionEn,
                    "descriptionAr" => $this->descriptionAr,
                    "requestReason" => $this->requestReason
        ];
    }
}
