<?php

namespace Blinqpay\PaymentRouter\Service;

use Blinqpay\PaymentRouter\Contracts\PaymentProcessors;
use InvalidArgumentException;

class PaymentProcessorService
{
    protected array $processors = [];

    public function __construct()
    {
        $this->initiateProcessors(config('payment-router.processors'));
    }

    public function initiateProcessors(array $processors)
    {
        foreach ($processors as $key => $processor) {
            $this->registerProcessor($processor);
        }
    }

    public function registerProcessor(array $config)
    {
        $processor = new $config['class'];

        if ($processor instanceof PaymentProcessors) {
            $this->processors[$config['name']] = $processor;
        } else {
            throw new InvalidArgumentException("Invalid payment processor driver");
        }
    }

    public function getAllProcessors(): array
    {
        return $this->processors;
    }
}