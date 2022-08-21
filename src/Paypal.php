<?php

namespace BlahteSoftware\BsPaypal;

use BlahteSoftware\BsPaypal\Contracts\PaypalCoreInterface;
use BlahteSoftware\BsPaypal\Contracts\PaypalInterface;
use Exception;

class Paypal implements PaypalInterface {
    /**
     * @var \BlahteSoftware\BsPaypal\Contracts\PaypalCoreInterface
     */
    protected $core;

    /**
     * @var \BlahteSoftware\BsPaypal\Contracts\PaypalInterface
     */
    protected static $instance;

    private function __construct(PaypalCoreInterface $paypalCore)
    {
        $this->core = $paypalCore;
    }

    public static function getInstance(PaypalCoreInterface $paypalCore = null): PaypalInterface
    {
        if( is_null($paypalCore) ) {
            if( is_null(static::$instance) ) {
                if( function_exists('app') && method_exists(app(), 'make') && method_exists(app(), 'bound') ) {
                    if(app()->bound(PaypalCoreInterface::class)) {
                        return static::$instance = new static(app()->make(PaypalCoreInterface::class));
                    }
                }
                throw new Exception("Paypal Core Object Not Found.");
            }
            if( !static::$instance instanceof PaypalInterface ) {
                throw  new Exception("Invalid Paypal Instance.");
            }
            return static::$instance;
        }
        if( is_null(static::$instance) ) {
            static::$instance = new static($paypalCore);
        }
        return static::$instance;
    }

    public function getAccessToken() : string {
        $response = $this->core->generateAccessToken();
        if(! property_exists($response['success'], 'access_token') ) {
            throw new Exception("Failed to fetch access token.");
        }
        return $response['success']->access_token;
    }

    /**
     * @see https://developer.paypal.com/docs/api/orders/v2/
     */
    public function createOrder(
        string $requestId,
        string $referenceId,
        string $amount,
        string $currencyCode = "USD",
        bool $preferCompleteRepresentation = false
    ) : array {
        $url = $this->core->url("/v2/checkout/orders");
        $response = [
            'success' => null,
            'error' => null,
            'code' => null
        ];
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer {$this->getAccessToken()}",
            "Paypal-Request-Id: {$requestId}"
        ];
        if($preferCompleteRepresentation == true) $headers[] = "Prefer: return=representation"; 
        $body = [
            'intent' => 'AUTHORIZE',
            "purchase_units" => [
                "reference_id" => $referenceId,
                "amount" => [
                    "currency_code" => $currencyCode,
                    "value" => $amount
                ]
            ]
        ];
        $c = curl_init();
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($c, CURLOPT_HEADER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($body));
        $response['code'] = curl_getinfo($c, CURLINFO_HTTP_CODE);
        $response['success'] = json_decode(curl_exec($c));
        if(curl_errno($c)) $response['error'] = curl_error($c);
        curl_close($c);
        return $response;
    }
}