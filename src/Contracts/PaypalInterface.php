<?php 

namespace BlahteSoftware\BsPaypal\Contracts;

interface PaypalInterface {
    /**
     * Get the Paypal instance.
     * 
     * @param \BlahteSoftware\BsPaypal\Contracts\PaypalCoreInterface
     * @return \BlahteSoftware\BsPaypal\Contracts\PaypalInterface
     */
    public static function getInstance(PaypalCoreInterface $paypalCore = null) : PaypalInterface;
}