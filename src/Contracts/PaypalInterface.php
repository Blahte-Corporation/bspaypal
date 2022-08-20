<?php 

namespace BlahteSoftware\BsPaypal\Contracts;

use PaypalAuthenticationInterface;

interface PaypalInterface extends PaypalAuthenticationInterface {
    /**
     * Get the Paypal instance.
     * 
     * @param \BlahteSoftware\BsPaypal\Contracts\PaypalCoreInterface
     * @return \BlahteSoftware\BsPaypal\Contracts\PaypalInterface
     */
    public static function getInstance(PaypalCoreInterface $paypalCore = null) : PaypalInterface;
}