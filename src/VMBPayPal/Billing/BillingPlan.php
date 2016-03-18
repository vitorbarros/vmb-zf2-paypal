<?php
namespace VMBPayPal\Billing;

use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;

abstract class BillingPlan extends Plan
{

    private $plan;
    protected $config = array();

    public function __construct(Plan $plan, PaymentDefinition $paymentDefinition)
    {
        $this->plan = $plan;
        if (!$this instanceof Plan && !$this instanceof PaymentDefinition) {
            throw new \Exception("{$this} must be instance of PayPal\\Api\\Plan and instance of PayPal\\Api\\PaymentDefinition");
        }
        $this->config = include __DIR__ . '/../config/billing.config.php';
    }

    protected function arrayDadosVerify(array $dados, $methodCondfg) {

        $diff = array();
        if(isset($this->config[$methodCondfg])) {

            $diff = array_diff_key($this->config[$methodCondfg], $dados);
            if(!empty($diff)) {
                throw new \Exception("Array dados is invalid, please check config billing file");
            }

        }else{
            throw new \Exception("undefined index {$methodCondfg}, please check config billing file");
        }

    }

}