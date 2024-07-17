<?php

namespace Blinqpay\PaymentRouter\Tests;

use Blinqpay\PaymentRouter\PaymentRouter;
use Blinqpay\PaymentRouter\Service\PaymentProcessorService;
use Exception;
use InvalidArgumentException;

class PaymentProcessorTest extends TestCase
{
    /**
     * Test paystack processor selection
     */
    public function testPaystackProcessorSelection()
    {
        $paymentRouter = $this->app->make(PaymentRouter::class);

        $processor = $paymentRouter->processRequest(100, 'ZAR');

        $this->assertEquals('Payment processed successfully through paystack', $processor);
    }

    /**
     * Test flutterwave processor selection
     */
    public function testFlutterwaveProcessorSelection()
    {
        $paymentRouter = $this->app->make(PaymentRouter::class);

        $processor = $paymentRouter->processRequest(100, 'CAD');

        $this->assertEquals('Payment processed successfully through flutterwave', $processor);
    }

    /**
     * Test payment processor selection based on priority
     */
    public function testPaymentProcessorSelectionBasedOnPriority()
    {
        $paymentRouter = $this->app->make(PaymentRouter::class);

        $processor = $paymentRouter->processRequest(100, 'USD');

        $this->assertEquals('Payment processed successfully through flutterwave', $processor);
    }

    /**
     * Test paystack payment processor selection with invalid currency
     */
    public function testPaystackProcessorSelectionWithInvalidCurrency()
    {
        $this->expectException(Exception::class);

        $this->expectExceptionMessage('No available payment provider for the given currency');

        $paymentRouter = $this->app->make(PaymentRouter::class);

        $paymentRouter->processRequest(100, 'EUR');
    }

    /**
     * Test flutterwave payment processor selection with invalid currency
     */
    public function testFlutterwaveProcessorSelectionWithInvalidCurrency()
    {
        $this->expectException(Exception::class);

        $this->expectExceptionMessage('No available payment provider for the given currency');

        $paymentRouter = $this->app->make(PaymentRouter::class);

        $processor = $paymentRouter->processRequest(100, 'GBP');
    }

    /**
     * Test payment processor selection with invalid amount
     */
    public function testPaymentProcessorSelectionWithInvalidAmount()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('Invalid amount');

        $paymentRouter = $this->app->make(PaymentRouter::class);

        $paymentRouter->processRequest(0, 'USD');
    }

    /**
     * Test remove processor
     */
    public function testRemoveProcessor()
    {
        $paymentRouter = $this->app->make(PaymentRouter::class);

        $paymentProcessorService = $this->app->make(PaymentProcessorService::class);

        $paymentProcessorService->registerProcessor([
            'name' => 'paystack',
            'transaction_cost' => 1.5,
            'priority' => 1,
            'currencies' => ['NGN', 'USD', 'ZAR'],
            'status' => 'Active',
            'class' => \Blinqpay\PaymentRouter\Clients\Paystack::class,
        ]);

        $paymentProcessorService->registerProcessor([
            'name' => 'flutterwave',
            'transaction_cost' => 1.4,
            'priority' => 2,
            'currencies' => ['NGN', 'USD', 'CAD'],
            'status' => 'Active',
            'class' => \Blinqpay\PaymentRouter\Clients\Flutterwave::class,
        ]);

        $updatedProcessor = $paymentRouter->removeProcessor('paystack');

        $this->assertArrayNotHasKey('paystack', $updatedProcessor);
        $this->assertArrayHasKey('flutterwave', $updatedProcessor);
        $this->assertCount(1, $updatedProcessor);
    }

    /**
     * Test update processor
     */
    public function testUpdateProcessor()
    {
        $paymentRouter = $this->app->make(PaymentRouter::class);

        $paymentProcessorService = $this->app->make(PaymentProcessorService::class);

        $paymentProcessorService->registerProcessor([
            'name' => 'paystack',
            'transaction_cost' => 1.5,
            'priority' => 1,
            'currencies' => ['NGN', 'USD', 'ZAR'],
            'status' => 'Active',
            'class' => \Blinqpay\PaymentRouter\Clients\Paystack::class,
        ]);

        $paymentProcessorService->registerProcessor([
            'name' => 'flutterwave',
            'transaction_cost' => 1.4,
            'priority' => 2,
            'currencies' => ['NGN', 'USD', 'CAD'],
            'status' => 'Active',
            'class' => \Blinqpay\PaymentRouter\Clients\Flutterwave::class,
        ]);

        $updatedProcessor = $paymentRouter->updateProcessor('paystack', [
            'name' => 'paystack',
            'transaction_cost' => 1.5,
            'priority' => 1,
            'currencies' => ['NGN', 'USD', 'ZAR'],
            'status' => 'Inactive',
            'class' => \Blinqpay\PaymentRouter\Clients\Paystack::class,
        ]);


        $this->assertEquals('Inactive',  $updatedProcessor['status']);
        $this->assertEquals(1, $updatedProcessor['priority']);
        $this->assertEquals(1.5, $updatedProcessor['transaction_cost']);
    }
}
