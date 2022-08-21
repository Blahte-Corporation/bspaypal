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

    /**
     * @see https://developer.paypal.com/docs/api/orders/v2/
     * 
     * @param string $requestId
     * @param string $body
     * @param bool $preferCompleteRepresentation
     * @return array
     */
    public function createOrder(
        string $requestId,
        string $body,
        bool $preferCompleteRepresentation = true
    ) : array;
}