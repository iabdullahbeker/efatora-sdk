<?php

namespace Iabdullahbeker\EfatoraSdk;

use Iabdullahbeker\EfatoraSdk\Singletons\EfatoraAuthentication;
use Iabdullahbeker\EfatoraSdk\Utils\EfatoraProduct;
use Iabdullahbeker\EfatoraSdk\Utils\EfatoraProducts;
use Illuminate\Support\Facades\Http;

class EFatoraSdk{

    private $token = null;

    public function __construct()
    {
        $auth_sigleton = EfatoraAuthentication::getInstance();
        $this->token = $auth_sigleton->token;
    }
    // public $token;
    // public function Authenticate($client_id,$client_secret){
    //     $client = new \GuzzleHttp\Client();
    //     $response = $client->post(config('efatora.AUTH_URL'), [
    //         'form_params' => [
    //             'grant_type' => 'client_credentials',
    //             'client_id' => $client_id,
    //             'client_secret' => $client_secret,
    //             'scope' => 'InvoicingAPI',
    //         ]
    //     ]);
    //     $response_json = json_decode($response->getBody(),true);
    //     $this->token = $response_json['access_token'];
    //     return $this;
    // }

    public function createEGS(EfatoraProducts $products){
        // dd($products->toArray());
        // $auth_sigleton = EfatoraAuthentication::getInstance();
        // dd($auth_sigleton->token);
        $response = Http::withToken(
           $this->token
        )->withBody(json_encode($products->toArray()), 'application/json')
        ->post(config('efatora.PRODUCT_CODING_URL'));

        return json_decode($response->getBody(),true);
    }

    public function submitDocument(array $docs){
        // dd($docs,$this->token);
        // dd($docs);
        $response = Http::withToken(
            $this->token
        )->post(config('efatora.DOCUMENT_SUBMISSION'), $docs);

        $response_json = json_decode($response->getBody(), true);

        return $response_json;
    }


}
