<?php

namespace Blinqpay\PaymentRouter\Tests;

use Blinqpay\PaymentRouter\Service\PaymentProcessorService;
use Exception;
use InvalidArgumentException;

class PaymentProcessorTest extends TestCase
{
    public function testPaystackProcessorSelection()
    {
        $paymentProcessorService = new PaymentProcessorService();

        $processor = $paymentProcessorService->processRequest(100, 'ZAR');

        $this->assertEquals('Payment processed successfully through paystack', $processor);

    }

    public function testFlutterwaveProcessorSelection()
    {
        $paymentProcessorService = new PaymentProcessorService();

        $processor = $paymentProcessorService->processRequest(100, 'CAD');

        $this->assertEquals('Payment processed successfully through flutterwave', $processor);
    }

    public function testPaymentProcessorSelectionBasedOnPriority()
    {
        $paymentProcessorService = new PaymentProcessorService();

        $processor = $paymentProcessorService->processRequest(100, 'USD');

        $this->assertEquals('Payment processed successfully through flutterwave', $processor);
    }

    public function testPaystackProcessorSelectionWithInvalidCurrency()
    {
        $this->expectException(Exception::class);

        $this->expectExceptionMessage('No available payment provider for the given currency');

        $paymentProcessorService = new PaymentProcessorService();

        $paymentProcessorService->processRequest(100, 'EUR');
    }

    public function testFlutterwaveProcessorSelectionWithInvalidCurrency()
    {
        $this->expectException(Exception::class);

        $this->expectExceptionMessage('No available payment provider for the given currency');

        $paymentProcessorService = new PaymentProcessorService();

        $paymentProcessorService->processRequest(100, 'GBP');
    }

    public function testPaymentProcessorSelectionWithInvalidAmount()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('Invalid amount');

        $paymentProcessorService = new PaymentProcessorService();

        $paymentProcessorService->processRequest(0, 'USD');
    }

    // test removeProcessor method
    public function testRemoveProcessor()
    {
        $paymentProcessorService = new PaymentProcessorService();

        $paymentProcessorService->removeProcessor('paystack');

        $this->expectException(Exception::class);

        $this->expectExceptionMessage('No available payment provider for the given currency');

        $paymentProcessorService->processRequest(100, 'ZAR');
    }

}
