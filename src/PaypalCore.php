<?php 

namespace BlahteSoftware\BsPaypal;

use BlahteSoftware\BsPaypal\Contracts\PaypalCoreInterface;

class PaypalCore implements PaypalCoreInterface {
    protected bool $live = false;
    protected string $account;
    protected string $client_id;
    protected string $secret;

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
    }
}