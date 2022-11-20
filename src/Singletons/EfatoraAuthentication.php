<?php
namespace Iabdullahbeker\EfatoraSdk\Singletons;


// General singleton class.
class EfatoraAuthentication {
  // Hold the class instance.
  private static $instance = null;
  public $token = null;

  // The constructor is private
  // to prevent initiation with outer code.
  private function __construct()
  {
    $client = new \GuzzleHttp\Client();
        $response = $client->post(config('efatora.AUTH_URL'), [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => config('efatora.CLIENT_ID'),
                'client_secret' => config('efatora.CLIENT_SECRET'),
                'scope' => 'InvoicingAPI',
            ]
        ]);
        $response_json = json_decode($response->getBody(),true);
        $this->token = $response_json['access_token'];
  }

  // The object is created from within the class itself
  // only if the class has no instance.
  public static function getInstance()
  {
    if (self::$instance == null)
    {
      self::$instance = new EfatoraAuthentication();
    }

    return self::$instance;
  }
}
