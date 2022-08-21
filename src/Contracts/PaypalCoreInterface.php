<?php

namespace BlahteSoftware\BsPaypal\Contracts;

interface PaypalCoreInterface extends PaypalAuthenticationInterface {
    public function url(string $path) : string;
}
