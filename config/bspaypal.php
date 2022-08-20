<?php

$COMPOSER_JSON_PATH = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'composer.json';
$PAYPAL_ENV = env('PAYPAL_ENV', 'sandbox');
$LIVE = $PAYPAL_ENV == 'live';

return [
    'account' => $LIVE ? env('PAYPAL_ACCOUNT') : env('PAYPAL_SANDBOX_ACCOUNT'),
    'client_id' => $LIVE ? env('PAYPAL_CLIENT_ID') : env('PAYPAL_SANDBOX_CLIENT_ID'),
    'secret' => $LIVE ? env('PAYPAL_SECRET') : env('PAYPAL_SANDBOX_SECRET')
];
