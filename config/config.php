<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'processors' => [
        'flutterwave' => [
            'priority' => 1, // Lower value indicates higher priority
            'currencies' => ['NGN'], // Supported currencies
            'min_amount' => 100, // Minimum transaction amount
            'class' => \App\Services\FlutterwavePaymentClient::class,
        ],
        'paystack' => [
            'priority' => 2, // Lower value indicates higher priority
            'currencies' => ['NGN'], // Supported currencies
            'min_amount' => 100, // Minimum transaction amount
            'class' => \App\Services\PaystackPaymentClient::class,
        ],
    ],
];