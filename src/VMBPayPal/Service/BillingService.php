<?php
namespace VMBPayPal\Service;

use VMBPayPal\Billing\BillingPlan;

class BillingService
{
    public function newBillingPlan($url, array $dados)
    {

        $billing = new BillingPlan($dados);
        return $billing->newBillingPlan($url);

    }
}