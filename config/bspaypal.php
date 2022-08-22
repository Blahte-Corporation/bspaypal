<?php

$COMPOSER_JSON_PATH = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'composer.json';
$PAYPAL_ENV = env('PAYPAL_ENV', 'sandbox');
$LIVE = $PAYPAL_ENV == 'live';

return [
    'account' => $LIVE ? env('PAYPAL_ACCOUNT') : env('PAYPAL_SANDBOX_ACCOUNT'),
    'client_id' => $LIVE ? env('PAYPAL_CLIENT_ID') : env('PAYPAL_SANDBOX_CLIENT_ID'),
    'secret' => $LIVE ? env('PAYPAL_SECRET') : env('PAYPAL_SANDBOX_SECRET'),
    'db' => [
        'host'      => env('DB_HOST', '127.0.0.1'),
        'port'      => env('DB_PORT', '3306'),
        'driver'    => env('DB_DRIVER', 'mysql'),
        'database'  => env('DB_DATABASE'),
        'username'  => env('DB_USERNAME'),
        'password'  => env('DB_PASSWORD'),
        'charset'   => env('DB_CHARSET', 'utf8mb4')
    ]
];
