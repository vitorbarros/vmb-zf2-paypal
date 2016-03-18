<?php
namespace VMBPayPal\Service;

use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use VMBPayPal\Billing\BillingPlan;

class BillingService extends BillingPlan
{

    private $plan;
    private $paymentDefinition;

    public function __construct(Plan $plan, PaymentDefinition $paymentDefinition)
    {
        parent::__construct($plan, $paymentDefinition);
        $this->plan = $plan;
        $this->paymentDefinition = $paymentDefinition;
    }

    public function newBillingPlan(array $dados)
    {
        try{

            $this->arrayDadosVerify($dados,'newBillingPlan');

            //starting the billing plan creating
            foreach($dados as $method => $dado) {
                $setMethod = 'set' . ucfirst($method);
                if(!array($dado)) {
                    $this->plan->$setMethod($dado);
                }
            }

            echo '<pre>';
            print_r($this->plan);
            exit;

        }catch(\Exception $e) {

        }

    }

}