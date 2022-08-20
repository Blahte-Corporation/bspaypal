<?php 

namespace BlahteSoftware\BsPaypal;

use BlahteSoftware\BsPaypal\Contracts\PaypalCoreInterface;

class PaypalCore implements PaypalCoreInterface {
    protected bool $live = false;
    protected string $account;
    protected string $client_id;
    protected string $secret;
    protected string $credentials;

    public function __construct(
        bool $live,
        string $account,
        string $client_id,
        string $secret
    ) {
        $this->live = $live;
        $this->account = $account;
        $this->client_id = $client_id;
        $this->secret = $secret;
        $this->credentials = base64_encode("{$this->client_id}:{$this->secret}");
    }

    /**
     * @inheritdoc
     */
    public function generateAccessToken(): array
    {
        $url = "https://api-m.sandbox.paypal.com/v1/oauth2/token";
        $response = [
            'success' => null,
            'error' => null,
            'code' => null
        ];
        $request_data = [
            'grant_type' => 'client_credentials'
        ];
        $c = curl_init();
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Content-Type: application/x-www-form-urlencoded",
            "Authorization: Basic {$this->credentials}"
        ]);
        curl_setopt($c, CURLOPT_HEADER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($request_data));
        $response['code'] = curl_getinfo($c, CURLINFO_HTTP_CODE);
        $response['success'] = curl_exec($c);
        if(curl_errno($c)) $response['error'] = curl_error($c);
        curl_close($c);
        return $response;
    }
}
