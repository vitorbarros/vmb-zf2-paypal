<?php
namespace VMBPayPal\Billing;

use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\ChargeModel;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

abstract class BillingPlan extends Plan
{

    private $paymentDefinition;
    private $chargeModel;
    private $merchantPreferences;
    private $method;
    private $credentialsConfig;
    private $context;

    protected $config = array();

    public function __construct(PaymentDefinition $paymentDefinition)
    {

        $this->paymentDefinition = $paymentDefinition;
        $this->chargeModel = new ChargeModel();
        $this->merchantPreferences = new MerchantPreferences();

        $this->config = include __DIR__ . '/../config/billing.config.php';
        $this->credentialsConfig = include __DIR__ . '/../config/credentials.config.php';

    }

    /**
     * Método que recebe todas as requisições e chama os métodos privados
     * @param $dados
     * @param $method
     * @return mixed
     */
    public function billingRequest($dados, $method)
    {
        $this->context = new ApiContext(new OAuthTokenCredential($this->credentialsConfig['client_id'], $this->credentialsConfig['client_secret']));
        return $this->$method($dados);
    }

    private function newBillingPlan(array $dados, $defautlClass = 'plan')
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
                        $this->$setMethod($dado);
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

            $this->setPaymentDefinitions(array($this->paymentDefinition));
            $this->setMerchantPreferences($this->merchantPreferences);

            $retorno = $this->create($this->context);

            return $retorno->getId();

        } catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }

    }

    private function getBillingPlan($billingId)
    {

        if($billingId != null) {
            try{
                return $this::get($billingId, $this->context);
            }catch(\Exception $e) {
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