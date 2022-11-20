<?php


namespace App\Services\Tax;


use App\Models\customers;
use App\Models\Product;
use App\Models\Receipt;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DocumentSubmitter
{
    /**
     * @var Collection
     */
    private $receipt;
    /**
     * @var customers
     */
    private $receiver;
    /** @var array */
    private $config;

    private $items;

    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
        $this->receiver = $receipt->customers;
        $this->config = require 'config.php';
        $this->items = $this->getItems();
    }

    private function getItems()
    {
        return Product::query()
            ->join("receipt_items", "receipt_items.product", "products.id")
            ->select([
                "products.*",
                DB::raw("receipt_items.quantity as item_quantity"),
                DB::raw("receipt_items.piece_price as item_price_each"),
                DB::raw("receipt_items.discount as item_discount"),
            ])
            ->where("receipt_items.receipt_id", $this->receipt->id)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function send(): array
    {
        $total = 0;
        $total_discount = 0;
        $total_tax = 0;
        $invoiceLines = $this->items->map(function ($item) use (&$total,&$total_discount,&$total_tax){
            $total += ($item->item_quantity * $item->item_price_each);
            $total_discount += $item->item_discount;
            $tax = ($item->item_quantity * $item->item_price_each * 0.14);
            $total_tax += $tax;
            return [
                "description" => $item->description,
                "itemType" => "EGS",
                "itemCode" => (string)$item->item_code,
                "unitType" => "EA",
                "quantity" => (float)$item->item_quantity,
                "internalCode" => (string)$item->id,
                "salesTotal" => $item->item_quantity * $item->item_price_each, // piece price
                "total" => $item->item_quantity * $item->item_price_each + $tax , // piece price * quantity
                "valueDifference" => 0,
                "totalTaxableFees" => 0,
                "netTotal" => $item->item_quantity * $item->item_price_each,
                "itemsDiscount" => 0,
                "unitValue" => [
                    "currencySold" => "EGP",
                    "amountEGP" => $item->item_price_each
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
        });


        $arr = [
            "documents" => [
                [
                    "issuer" => $this->config['issuer'],
                    "receiver" => [
                        "address" => [
                            "country" => "EG",
                            "governate" => "Egypt",
                            "regionCity" => $this->receiver->address,
                            "street" => $this->receiver->address,
                            "buildingNumber" => "Bldg. 0",
                            "postalCode" => "68030",
                            "floor" => "1",
                            "room" => "123",
                            "landmark" => "7660 Melody Trail",
                            "additionalInformation" => $this->receiver->address,
                        ],
                        "type" => "B",
                        "id" => "123456789",
                        "name" => $this->receiver->name
                    ],
                    "documentType" => "I",
                    "documentTypeVersion" => "0.9",
                    "dateTimeIssued" => date('Y-m-d\Th:m:s'),
                    "taxpayerActivityCode" => $this->config['taxpayerActivityCode'],
                    "internalID" => Str::random(),
                    "salesOrderReference" => "1231",
                    "salesOrderDescription" => "Sales Order description",
                    "proformaInvoiceNumber" => "SomeValue",

                    "invoiceLines" => $invoiceLines->toArray(),

                    "totalDiscountAmount" => (float)$total_discount,
                    "totalSalesAmount" => $total,
                    "netAmount" => $total - $total_discount,
                    "taxTotals" => [
                        [
                            "taxType" => "T1",
                            "amount" => round($total_tax,5)
                        ]
                    ],
                    "totalAmount" => $total + $total_tax,
                    "extraDiscountAmount" => 0,
                    "totalItemsDiscountAmount" => (float)0,
                ]
            ]
        ];
        
    
//        dd($arr, $this->receipt);
        $token = session()->get('check_login_time_expaird')['access_token'] ?? null;
        if(is_null($token)){
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://id.preprod.eta.gov.eg/connect/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => '4b2d1f90-dae8-486f-ad96-56a3203f0fde',
                    'client_secret' => '0824f22a-8ff3-43f2-ae3f-e8ccad194a73',
                    'scope' => 'InvoicingAPI',
                ]
            ]);
            $response_json = json_decode($response->getBody(),true);
            $token =  $response_json['access_token'];
        }
        $response = Http::withToken(
            $token
        )->post('https://api.preprod.invoicing.eta.gov.eg/api/v1/documentsubmissions', $arr);

        $response_json = json_decode($response->getBody(), true);

        return $response_json;
//        array:3 [▼
//  "submissionId" => "KFCPNB69MJW99RMWX0RXV0BG10"
//  "acceptedDocuments" => array:1 [▼
//    0 => array:4 [▼
//      "uuid" => "5052FE1CNHDCBN06X0RXV0BG10"
//      "longId" => "6DSXBVZCJZY3WMT1X0RXV0BG106pvhuH1661107888"
//      "internalId" => "2"
//      "hashKey" => "WhwaEWKYXTW73CO+oD4k3tEiG6GhklQnKFcidApyUT8="
//    ]
//  ]
//  "rejectedDocuments" => []
//]
    }
}