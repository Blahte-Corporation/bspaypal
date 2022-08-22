<?php

namespace BlahteSoftware\BsPaypal\Utils;

if(! defined('BSPAYPAL_TABLE_ACCESS_TOKENS') ) {
    define('BSPAYPAL_TABLE_ACCESS_TOKENS', 'bspaypal_access_tokens');
    define('BSPAYPAL_TABLE_REQUEST_IDS', 'bspaypal_request_ids');
    define('BSPAYPAL_TABLE_ORDER_NUMBERS', 'bspaypal_order_numbers');
    define('BSPAYPAL_TABLE_ORDERS', 'bspaypal_orders');

    define('BSPAYPAL_PDOSTATEMENT_ERROR', '00000');
}