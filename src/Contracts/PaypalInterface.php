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
     * @param string $requestId
     * @param string $referenceId
     * @param string $amount
     * @param string $currencyCode
     * @param bool $preferCompleteRepresentation
     * @return array
     */
    public function createOrder(
        string $requestId,
        string $referenceId,
        string $amount,
        string $currencyCode = "USD",
        bool $preferCompleteRepresentation = false
    ) : array;
}