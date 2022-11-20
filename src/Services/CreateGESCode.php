<?php


namespace Iabdullahbeker\EfatoraSdk\Services;

use Illuminate\Support\Facades\Http;

class CreateEGSCode
{

    public static function create($product){
        $arr = [
            "items"=>[
                [
                    "codeType"=>"EGS",
                    "parentCode"=>"99999999",
                    "itemCode"=>"EG-".rand(100000000, 999999999)."-".$product->id,
                    "codeName"=>$product->name_en,
                    "codeNameAr"=>$product->name_ar,
                    "activeFrom"=>date('Y-m-d\Th:m:s'),
                    // "activeTo"=>"2022-08-21T00:00:00.000",
                    "description"=>$product->description_en,
                    "descriptionAr"=>$product->description_ar,
                    "requestReason"=>$product->request_reason
                ]
            ]
        ];


        $response = Http::withToken(
            session()->get('check_login_time_expaird')['access_token']
        )->withBody(json_encode($arr), 'application/json')
        ->post('https://api.preprod.invoicing.eta.gov.eg/api/v1.0/codetypes/requests/codes');

        return json_decode($response->getBody(),true);
    }
}