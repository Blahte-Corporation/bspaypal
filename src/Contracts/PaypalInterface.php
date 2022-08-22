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

    public function setDatabaseInstance(DatabaseInterface $db);

    public function ensureDatabaseTablesExist();

    /**
     * @see https://developer.paypal.com/docs/api/orders/v2/
     * 
     * Example Request Body
     /
        {
            "intent": "CAPTURE",
            "purchase_units": [
                {
                    "items": [
                        {
                            "name": "Donation",
                            "description": "Donation to CheHerma",
                            "quantity": "1",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "32.00"
                            }
                        }
                    ],
                    "amount": {
                        "currency_code": "USD",
                        "value": "32.00",
                        "breakdown": {
                            "item_total": {
                                "currency_code": "USD",
                                "value": "32.00"
                            }
                        }
                    }
                }
            ],
            "application_context": {
                "return_url": "https://cheherma.org",
                "cancel_url": "https://cheherma.org"
            }
        }
     /
     * 
     * Example Response Body
     /
        {
            "id": "911825740G540840D",
            "intent": "CAPTURE",
            "status": "CREATED",
            "purchase_units": [
                {
                    "reference_id": "default",
                    "amount": {
                        "currency_code": "USD",
                        "value": "32.00",
                        "breakdown": {
                            "item_total": {
                                "currency_code": "USD",
                                "value": "32.00"
                            }
                        }
                    },
                    "payee": {
                        "email_address": "sb-bvghj19975793@business.example.com",
                        "merchant_id": "4ZEEXH5TB4B7A"
                    },
                    "items": [
                        {
                            "name": "Donation",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": "32.00"
                            },
                            "quantity": "1",
                            "description": "Donation to CheHerma"
                        }
                    ]
                }
            ],
            "create_time": "2022-08-21T16:00:34Z",
            "links": [
                {
                    "href": "https://api.sandbox.paypal.com/v2/checkout/orders/911825740G540840D",
                    "rel": "self",
                    "method": "GET"
                },
                {
                    "href": "https://www.sandbox.paypal.com/checkoutnow?token=911825740G540840D",
                    "rel": "approve",
                    "method": "GET"
                },
                {
                    "href": "https://api.sandbox.paypal.com/v2/checkout/orders/911825740G540840D",
                    "rel": "update",
                    "method": "PATCH"
                },
                {
                    "href": "https://api.sandbox.paypal.com/v2/checkout/orders/911825740G540840D/capture",
                    "rel": "capture",
                    "method": "POST"
                }
            ]
        }
     /
     * 
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

    public function requestForApproval(string $requestId) : PaypalInterface;

    public function capture(string $requestId) : PaypalInterface;

    public function getRequestId() : string;

    public function setPayerId(string $requestId, string $payerId) : PaypalInterface;

    public function setCancelled(string $requestId) : PaypalInterface;
    
}