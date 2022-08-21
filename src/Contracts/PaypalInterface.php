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
     * @param array $items
     * @param string $amount
     * @param string $currencyCode
     * @param bool $preferCompleteRepresentation
     * @param string $returnUrl
     * @param string $cancelUrl
     * @return array
     */
    public function createOrder(
        string $requestId,
        array $items,
        string $amount,
        string $currencyCode = "USD",
        bool $preferCompleteRepresentation = true,
        string $returnUrl,
        string $cancelUrl
    ) : array;
}