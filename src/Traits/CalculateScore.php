<?php

namespace Blinqpay\PaymentRouter\Traits;

trait CalculateScore
{
    public function getScore(array $config, array $criteria, string $currency): float|int
    {
        $score = 0;

        if (isset($criteria['transaction_cost']) && $criteria['transaction_cost']) {
            $score += (1 - $config['transaction_cost'] / 100) * $criteria['transaction_cost'];
        }

        if (isset($criteria['priority']) && $criteria['priority']) {
            $score += ($config['priority'] / 100) * $criteria['priority'];
        }

        if (isset($criteria['currencies'])) {
            if (in_array($currency, $config['currencies'])) {
                $score += $criteria['currencies'];
            }
        }

        return $score;
    }
}