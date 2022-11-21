# Efatora SDK

This sdk helps developers to integrate easily with egypt Efatora.

----------

### installation
```
  composer require iabdullahbeker/efatora-sdk
```

### publish config
```
    php artisan vendor:publish --tag=config
```
### update config file "config/efatora.php"
```php
[
    "TAX_PAYER_ACTIVITY_CODE" => "",  // from portal 
    "PRODUCT_CODING_URL" => "https://api.preprod.invoicing.eta.gov.eg/api/v1.0/codetypes/requests/codes",
    "AUTH_URL" => "https://id.preprod.eta.gov.eg/connect/token",
    "CLIENT_ID" => "92d93edc-d330-4ea8-b969-65861b531e6b", // from portal 
    "CLIENT_SECRET" => "0e18b1c5-785a-4644-98c2-48eaf0ebe276", // from portal 
    "DOCUMENT_SUBMISSION" => "https://api.preprod.invoicing.eta.gov.eg/api/v1/documentsubmissions",
    "ISSUER" => [ // the data of issuer of the invoice 
        "address" => [
            "branchID" => "0",
            "country" => "EG",
            "governate" => "Cairo",
            "regionCity" => "Nasr City",
            "street" => "580 Clementina Key",
            "buildingNumber" => "Bldg. 0",
            "postalCode" => "68030",
            "floor" => "1",
            "room" => "123",
            "landmark" => "7660 Melody Trail",
            "additionalInformation" => "beside Townhall"
        ],
        "type" => "B",
        "id" => "376877448",
        "name" => "ايمان مجدى وشريكتها"
    ],
];
```
### Sample code to coding the products

```php
use Iabdullahbeker\EfatoraSdk\Facades\EFatoraSdk;
use Iabdullahbeker\EfatoraSdk\Utils\EfatoraProduct;
use Iabdullahbeker\EfatoraSdk\Utils\EfatoraProducts;

$product1 = new EfatoraProduct([
            "parentCode"=>"99999999",
            "productId"=> "123456",
            "nameEn"=>"aa",
            "nameAr"=>"aa",
            "dateTime"=>date('Y-m-d\Th:i:s'),
            // "activeTo"=>"2022-08-21T00:00:00.000",
            "descriptionEn"=>"asd",
            "descriptionAr"=>"asd",
            "requestReason"=>"asd"
        ]);
        $product2 = new EfatoraProduct([
            "parentCode"=>"99999999",
            "productId"=> "5689874",
            "nameEn"=>"aas",
            "nameAr"=>"aas",
            "dateTime"=>date('Y-m-d\Th:i:s'),
            // "activeTo"=>"2022-08-21T00:00:00.000",
            "descriptionEn"=>"asdl",
            "descriptionAr"=>"asdl",
            "requestReason"=>"asdl"
        ]);
        $products = new EfatoraProducts;
        $products->add($product1->toArray());
        $products->add($product2->toArray());
        $r = EFatoraSdk::createEGS($products); // response 
```

### Sample code to submit the invoice document

```php
use Iabdullahbeker\EfatoraSdk\Utils\EfatoraReceiptItem;
use Iabdullahbeker\EfatoraSdk\Facades\EFatoraSdk;
use Iabdullahbeker\EfatoraSdk\Utils\DocumentReceiver;
use Iabdullahbeker\EfatoraSdk\Utils\EfatoraDocuments;
use Iabdullahbeker\EfatoraSdk\Utils\InvoiceLines;


        $item = new EfatoraReceiptItem([
            'id' => '123456',
            'item_type' => 'EGS',
            'description' => 'description',
            'item_code' => 'EG-376877448-RS01',
            'item_quantity' => 2,
            'item_price_each' => 300,
        ]);

        $invoiceLines = new InvoiceLines;
        $invoiceLines->add($item->toArray());

        $receiver = new DocumentReceiver([
            "country" => "EG",
            "governate" => "Cairo",
            "regionCity" => "Giza",
            "street" => "Giza",
            "buildingNumber" => "Bldg. 0",
            "postalCode" => "68030",
            "floor" => "1",
            "room" => "123",
            "landmark" => "7660 Melody Trail",
            "additionalInformation" => "Giza",
            "receiver_type" => "B",
            "receiver_id" => "123456789",
            "receiver_name" => "Abdullah Mohamed"
        ]);

        $document = new EfatoraDocuments($invoiceLines, $receiver);

        $r = EFatoraSdk::submitDocument($document->toArray()); // response
```
