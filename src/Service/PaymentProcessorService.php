<?php

namespace Blinqpay\PaymentRouter\Service;

use Blinqpay\PaymentRouter\Contracts\PaymentProcessors;
use Exception;
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

    public function fetchAllProcessors(): array
    {
        return $this->processors;
    }

    public function updateProcessor(string $name, array $config)
    {
        if (isset($this->processors[$name])) {
            $this->registerProcessor(array_merge(
                collect($this->processors[$name])->collapse()->toArray(),
                $config
            ));
        } else {
            throw new InvalidArgumentException("Processor '$name' not found");
        }
    }

    public function removeProcessor(string $name)
    {
        if (isset($this->processors[$name])) {
            unset($this->processors[$name]);
        } else {
            throw new InvalidArgumentException("Processor '$name' not found");
        }
    }

    /**
     * @param float $amount
     * @param string $currency
     * @return string
     * @throws Exception
     */
    public function processRequest(float $amount, string $currency): string
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Invalid amount');
        }

        $bestProvider = $this->fetchBestProcessor($amount, $currency);

        if ((new $bestProvider['class']())->processPayment($amount, $currency)) {
            return "Payment processed successfully through " . $bestProvider['name'];
        } else {
            throw new Exception('Payment failed');
        }
    }

    /**
     * @param $amount
     * @param $currency
     * @return array
     * @throws Exception
     */
    private function fetchBestProcessor($amount, $currency): array
    {
        $availableProviders = array_filter($this->processors, function ($processor) use ($currency) {
            return $processor->isAvailable() && $processor->canProcessCurrency($currency);
        });

        if (empty($availableProviders)) {
            throw new Exception('No available payment provider for the given currency');
        }

        // Sort by fees and priority
        usort($availableProviders, function ($a, $b) use ($amount) {
            $aFees = $a->getFees($amount);
            $bFees = $b->getFees($amount);

            return $aFees <=> $bFees ?: $a->config['priority'] <=> $b->config['priority'];
        });

        return collect($availableProviders[0])->collapse()->toArray();
    }
}