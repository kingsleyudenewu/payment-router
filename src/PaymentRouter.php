<?php

namespace Blinqpay\PaymentRouter;

use Blinqpay\PaymentRouter\Service\PaymentProcessorService;
use Exception;
use InvalidArgumentException;

class PaymentRouter
{
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
     * @param string $name
     * @param array $config
     */
    public function removeProcessor(string $name): array
    {
        $processors = (new PaymentProcessorService())->getAllProcessors();

        if (isset($processors[$name])) {
            // filter out the processor to be removed
            return array_filter($processors, function ($processor) use ($name) {
                return collect($processor)->collapse()->toArray()['name'] !== $name;
             });
        } else {
            throw new InvalidArgumentException("Processor '$name' not found");
        }
    }

    public function updateProcessor(string $name, array $config): array
    {
        $processors = (new PaymentProcessorService());
        $allProcessors = $processors->getAllProcessors();

        if (isset($allProcessors[$name])) {
            $updatedProcessor = array_merge(
                collect($allProcessors[$name])->collapse()->toArray(),
                $config
            );

            $processors->registerProcessor($updatedProcessor);

            return $updatedProcessor;
        } else {
            throw new InvalidArgumentException("Processor '$name' not found");
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
        $availableProviders = array_filter((new PaymentProcessorService())->getAllProcessors(), function ($processor) use ($currency) {
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

        return collect(array_shift($availableProviders))->collapse()->toArray();
    }
}
