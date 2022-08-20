<?php 

namespace BlahteSoftware\BsPaypal\Contracts;

interface PaypalAuthenticationInterface {
    /**
     * generateAccessToken
     *      exchange your client ID and secret for an access token
     * 
     * 
     * Example Request
     /
    curl -v -X POST "https://api-m.sandbox.paypal.com/v1/oauth2/token" \
        -u "<CLIENT_ID>:<CLIENT_SECRET>" \
        -H "Content-Type: application/x-www-form-urlencoded" \
        -d "grant_type=client_credentials"
     /
     * 
     * Example Response
     /
        {
            "scope": "https://uri.paypal.com/services/invoicing https://uri.paypal.com/services/disputes/read-buyer https://uri.paypal.com/services/payments/realtimepayment https://uri.paypal.com/services/disputes/update-seller https://uri.paypal.com/services/payments/payment/authcapture openid https://uri.paypal.com/services/disputes/read-seller https://uri.paypal.com/services/payments/refund https://api-m.paypal.com/v1/vault/credit-card https://api-m.paypal.com/v1/payments/.* https://uri.paypal.com/payments/payouts https://api-m.paypal.com/v1/vault/credit-card/.* https://uri.paypal.com/services/subscriptions https://uri.paypal.com/services/applications/webhooks",
            "access_token": "A21AAFEpH4PsADK7qSS7pSRsgzfENtu-Q1ysgEDVDESseMHBYXVJYE8ovjj68elIDy8nF26AwPhfXTIeWAZHSLIsQkSYz9ifg",
            "token_type": "Bearer",
            "app_id": "APP-80W284485P519543T",
            "expires_in": 31668,
            "nonce": "2020-04-03T15:35:36ZaYZlGvEkV4yVSz8g6bAKFoGSEzuy3CQcz3ljhibkOHg"
        }
     /
        { 
            "success" => {
                "error":"unsupported_grant_type",
                "error_description":"Grant Type is NULL"
            },
            "error" => NULL,
            "code" => int(0) 
        } 
     * 
     */
    public function generateAccessToken() : array;

}