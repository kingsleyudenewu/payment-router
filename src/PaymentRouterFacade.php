<?php

namespace Blinqpay\PaymentRouter;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Blinqpay\PaymentRouter\Skeleton\SkeletonClass
 */
class PaymentRouterFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payment-router';
    }
}
