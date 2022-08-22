<?php

$body = [
    'intent' => 'CAPTURE',
    "purchase_units" => [
        [
            "items" => [
                [
                    "name" => "Donation",
                    "description" => "Donation to CheHerma",
                    "quantity" => "1",
                    "unit_amount" => [
                        "currency_code" => $currencyCode,
                        "value" => $amount
                    ]
                ]
            ],
            "amount" => [
                "currency_code" => $currencyCode,
                "value" => $amount,
                "breakdown" => [
                    "item_total" => [
                        "currency_code" => $currencyCode,
                        "value" => $amount
                    ]
                ]
            ]
        ]
    ],
    "application_context" => [
        "return_url" => $url,
        "cancel_url" => $url
    ]
];