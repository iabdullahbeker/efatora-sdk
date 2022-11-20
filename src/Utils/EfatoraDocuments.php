<?php

namespace Iabdullahbeker\EfatoraSdk\Utils;

use Illuminate\Support\Str;

class EfatoraDocuments
{

    private InvoiceLines $invoiceLines;
    private DocumentReceiver $receiver;

    public function __construct(InvoiceLines $invoiceLines, DocumentReceiver $receiver)
    {
        $this->invoiceLines = $invoiceLines;
        $this->receiver = $receiver;
    }

    public function toArray(): array
    {
        // dd(date('Y-m-d\TH:i:s'));
        return [
            "documents" => [
                [
                    "issuer" => config('efatora.ISSUER'),
                    "receiver" => $this->receiver->toArray(),
                    "documentType" => "I",
                    "documentTypeVersion" => "0.9",
                    "dateTimeIssued" => date('Y-m-d\TH:i:s'),
                    "taxpayerActivityCode" => config('efatora.TAX_PAYER_ACTIVITY_CODE'),
                    "internalID" => Str::random(),
                    "salesOrderReference" => "1231",
                    "salesOrderDescription" => "Sales Order description",
                    "proformaInvoiceNumber" => "SomeValue",

                    "invoiceLines" => $this->invoiceLines->toArray(),

                    "totalDiscountAmount" => (float)$this->invoiceLines->total_discount,
                    "totalSalesAmount" => $this->invoiceLines->total,
                    "netAmount" => $this->invoiceLines->total - $this->invoiceLines->total_discount,
                    "taxTotals" => [
                        [
                            "taxType" => "T1",
                            "amount" => round($this->invoiceLines->total_tax, 5)
                        ]
                    ],
                    "totalAmount" => $this->invoiceLines->total + $this->invoiceLines->total_tax,
                    "extraDiscountAmount" => 0,
                    "totalItemsDiscountAmount" => (float)0,
                ]
            ]
        ];
    }
}
