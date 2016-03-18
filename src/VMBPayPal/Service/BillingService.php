<?php
namespace VMBPayPal\Service;

use PayPal\Api\PaymentDefinition;
use VMBPayPal\Billing\BillingPlan;

class BillingService extends BillingPlan
{

    public function __construct(PaymentDefinition $paymentDefinition)
    {
        parent::__construct($paymentDefinition);
    }

}