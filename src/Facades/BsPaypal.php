<?php

namespace BlahteSoftware\BsPaypal\Facades;

use BlahteSoftware\BsPaypal\Contracts\PaypalInterface;
use Illuminate\Support\Facades\Facade;

class BsPaypal extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { 
        return PaypalInterface::class; 
    }
}
