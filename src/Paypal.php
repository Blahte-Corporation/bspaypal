<?php

namespace BlahteSoftware\BsPaypal;

use BlahteSoftware\BsPaypal\Contracts\PaypalCoreInterface;
use BlahteSoftware\BsPaypal\Contracts\PaypalInterface;
use Exception;

class Paypal implements PaypalInterface {
    protected PaypalCoreInterface $core;
    protected static PaypalInterface $instance;

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

    public function generateAccessToken() : array {
        return $this->core->generateAccessToken();
    }
}