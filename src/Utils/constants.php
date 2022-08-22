<?php

namespace BlahteSoftware\BsPaypal\Utils;

if(! defined('BSPAYPAL_TABLE_ACCESS_TOKENS') ) {
    define('BSPAYPAL_TABLE_ACCESS_TOKENS', 'bspaypal_access_tokens');
    define('BSPAYPAL_TABLE_REQUEST_IDS', 'bspaypal_request_ids');
    define('BSPAYPAL_TABLE_ORDER_NUMBERS', 'bspaypal_order_numbers');
    define('BSPAYPAL_TABLE_ORDERS', 'bspaypal_orders');

    define('BSPAYPAL_PDO_QUERY_OK', '00000');
    define('BSPAYPAL_DDL_TABLE_ACCESS_TOKENS', dirname(__FILE__, 2) . '/Sql/accessTokens.sql');
    define('BSPAYPAL_DDL_TABLE_REQUEST_IDS', dirname(__FILE__, 2) . '/Sql/requestIds.sql');
    define('BSPAYPAL_DDL_TABLE_ORDERS', dirname(__FILE__, 2) . '/Sql/orders.sql');

    define('DATE_YMDHIS', 'Y-m-d H:i:s');
}
