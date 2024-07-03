<?php

namespace Blinqpay\PaymentRouter\Contracts;

interface PaymentRouterAdapter
{
    public function processTransaction();
}