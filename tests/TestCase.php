<?php

namespace Blinqpay\PaymentRouter\Tests;

use Blinqpay\PaymentRouter\PaymentRouterServiceProvider;
use \Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
//    protected function setUp(): void
//    {
//        parent::setUp();
//    }

    protected function getPackageProviders($app): array
    {
        return [
            PaymentRouterServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('payment-router', [
            'driver' => 'flutterwave',
            'flutterwave' => [
                'public_key' => 'FLWPUBK_TEST-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx-X',
            ],
            'paystack' => [
                'public_key' => 'pk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
                'secret_key' => 'sk'
            ],
        ]);
    }
}