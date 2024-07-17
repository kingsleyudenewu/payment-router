<?php

namespace Blinqpay\PaymentRouter\Clients;

use Blinqpay\PaymentRouter\Contracts\PaymentProcessors;
use Blinqpay\PaymentRouter\Traits\CalculateScore;

class Paystack extends PaymentProcessors
{
    public function __construct()
    {
        parent::__construct(config('payment-router.processors.paystack'));
    }

    /**
     * @param $amount
     * @param $currency
     * @return bool
     */
    public function processPayment($amount, $currency): bool
    {
        if ($this->isAvailable() && $this->canProcessCurrency($currency)) {
            return true;
        }
        return false;
    }
}