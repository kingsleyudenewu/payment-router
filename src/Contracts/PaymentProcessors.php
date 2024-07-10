<?php

namespace Blinqpay\PaymentRouter\Contracts;

abstract class PaymentProcessors
{
    public function __construct(protected array $config)
    {
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->config['status'] === 'Active';
    }

    public function canProcessCurrency(string $currency): bool
    {
        return in_array($currency, $this->config['currencies']);
    }

    public function getFees(float $amount): float
    {
        return $amount * ($this->config['transaction_cost'] / 100);
    }

    abstract public function processPayment($amount, $currency);
}