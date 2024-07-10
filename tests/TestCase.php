<?php

namespace Blinqpay\PaymentRouter\Tests;

use Blinqpay\PaymentRouter\PaymentRouterServiceProvider;
use \Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            PaymentRouterServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('payment-router', [
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
                    'status' => 'Active',
                    'class' => \Blinqpay\PaymentRouter\Clients\Flutterwave::class,
                ],
            ],
        ]);
    }
}