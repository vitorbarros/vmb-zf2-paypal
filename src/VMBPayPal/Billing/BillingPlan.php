<?php
namespace VMBPayPal\Billing;

use PayPal\Api\Plan;
use PayPal\Common\PayPalModel;
use VMBPayPal\AbstractClass\AbstractModel;
use Zend\Json\Json;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;

abstract class BillingPlan extends AbstractModel
{

    public function __construct()
    {
        parent::__construct();
    }

    public function newBillingPlan(array $dados, $defautlClass = 'plan')
    {

        try {

            if ($this->method != null) {
                $this->changeConfigArray($this->method);
                $this->arrayDadosVerify($dados, 'newBillingPlan');

            } else {
                $this->arrayDadosVerify($dados, 'newBillingPlan');
            }

            //starting the billing plan creating
            foreach ($dados as $method => $dado) {

                $this->method = $method;

                if (!is_array($dado) || $method == 'amount') {

                    $setMethod = 'set' . ucfirst($method);

                    if ($defautlClass == 'plan') {
                        $this->plan->$setMethod($dado);
                    } else if ($defautlClass == 'paymentDefinition') {
                        $this->paymentDefinition->$setMethod($dado);
                    } else if ($defautlClass == 'chargeModel') {
                        $this->chargeModel->$setMethod($dado);
                        if ($method == 'amount') {
                            $this->paymentDefinition->setChargeModels(array($this->chargeModel));
                        }
                    } else if ($defautlClass == 'merchantPreferences') {
                        $this->merchantPreferences->$setMethod($dado);
                    }

                } else if ($method == 'paymentDefinition' || $method == 'chargeModel' || $method == 'merchantPreferences') {
                    switch ($method) {
                        case 'paymentDefinition':
                            return $this->newBillingPlan($dados[$method], 'paymentDefinition');
                            break;
                        case 'chargeModel':
                            return $this->newBillingPlan($dados[$method], 'chargeModel');
                            break;
                        case 'merchantPreferences':
                            return $this->newBillingPlan($dados[$method], 'merchantPreferences');
                            break;
                    }

                }
            }

            $this->plan->setPaymentDefinitions(array($this->paymentDefinition));
            $this->plan->setMerchantPreferences($this->merchantPreferences);

            $retorno = $this->plan->create($this->context);

            return $retorno->getId();

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function statusChangeBillingPlan($status, $billinPlanId)
    {

        if ($status != null && $billinPlanId != null) {
            try {

                $patch = new Patch();
                $value = new PayPalModel(Json::encode(array("state" => $status)));

                $patch->setOp('replace')
                    ->setPath('/')
                    ->setValue($value);

                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);

                $plan = $this->plan;
                $createdPlan = $plan::get($billinPlanId, $this->context);
                $createdPlan->update($patchRequest, $this->context);

                return $createdPlan->getId();

            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        throw new \Exception("Status and Plan id cannot be null");
    }

    public function getAllBillingPlan(array $parans = array())
    {
        try {
            return Plan::all($parans, $this->context);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getBillingPlan($billingId)
    {

        if ($billingId != null) {
            try {
                return Plan::get($billingId, $this->context);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        throw new \Exception("Plan id cannot be null");

    }

    public function deleteBillingPlan($billingId)
    {

        if ($billingId != null) {
            try {
                $planObj = $this->plan;
                $plan = $planObj::get($billingId);
                $plan->delete($this->context);
                return $plan->getId();
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        throw new \Exception("Plan id cannot be null");
    }

    private function arrayDadosVerify(array $dados, $methodCondfg)
    {

        $diff = array();
        if (isset($this->config[$methodCondfg])) {

            $diff = array_diff_key($this->config[$methodCondfg], $dados);
            if (!empty($diff)) {
                throw new \Exception("Array dados is invalid, please check config billing file");
            }

        } else {
            throw new \Exception("undefined index {$methodCondfg}, please check config billing file");
        }

    }

    private function changeConfigArray($configuration)
    {
        switch ($configuration) {
            case 'paymentDefinition':
                $this->config = array(
                    'newBillingPlan' => array(
                        'name' => '',
                        'type' => '',
                        'frequency' => '',
                        'frequencyInterval' => '',
                        'cycles' => '',
                        'amount' => array(
                            'value' => '',
                            'currency' => '',
                        ),
                        'chargeModel' => array(
                            'type' => '',
                            'amount' => array(
                                'value' => '',
                                'currency' => '',
                            ),
                            'merchantPreferences' => array(
                                'returnUrl' => '',
                                'cancelUrl' => '',
                                'autoBillAmount' => '',
                                'initialFailAmountAction' => '',
                                'maxFailAttempts' => ''
                            )
                        ),
                    )
                );
                break;

            case 'chargeModel' :
                $this->config = array(
                    'newBillingPlan' => array(
                        'type' => '',
                        'amount' => array(
                            'value' => '',
                            'currency' => '',
                        ),
                        'merchantPreferences' => array(
                            'returnUrl' => '',
                            'cancelUrl' => '',
                            'autoBillAmount' => '',
                            'initialFailAmountAction' => '',
                            'maxFailAttempts' => ''
                        )
                    ),
                );
                break;
            case 'merchantPreferences':
                $this->config = array(
                    'newBillingPlan' => array(
                        'returnUrl' => '',
                        'cancelUrl' => '',
                        'autoBillAmount' => '',
                        'initialFailAmountAction' => '',
                        'maxFailAttempts' => ''
                    ),
                );
                break;

        }
    }

}