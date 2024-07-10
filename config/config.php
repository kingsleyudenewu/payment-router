<?php

/*
 * You can place your custom package configuration in here.
 */


return [
    'processors' => [
        'paystack' => [
            'name' => 'paystack',
            'transaction_cost' => 1.5,
            'priority' => 1, // Lower value indicates higher priority
            'currencies' => ['NGN', 'USD', 'ZAR'], // Supported currencies
            'status' => 'Active',
            'class' => \Blinqpay\PaymentRouter\Clients\Paystack::class,
        ],
        'flutterwave' => [
            'name' => 'flutterwave',
            'transaction_cost' => 1.4,
            'priority' => 2, // Lower value indicates higher priority
            'currencies' => ['NGN', 'USD', 'CAD'], // Supported currencies
            'min_amount' => 100, // Minimum transaction amount
            'status' => 'Active',
            'class' => \Blinqpay\PaymentRouter\Clients\Flutterwave::class,
        ],
    ],
];