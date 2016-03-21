<?php
namespace VMBPayPal\AbstractClass;

use PayPal\Api\Agreement;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Payer;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\ChargeModel;
use PayPal\Api\ShippingAddress;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

abstract class AbstractModel
{

    protected $plan;
    protected $paymentDefinition;
    protected $chargeModel;
    protected $merchantPreferences;
    protected $context;
    protected $config;
    protected $method;
    protected $credentialsConfig;
    protected $agreement;
    protected $payer;
    protected $shippingAddress;

    public function __construct()
    {

        $this->credentialsConfig = include __DIR__ . '/../config/credentials.config.php';
        $this->config = include __DIR__ . '/../config/billing.config.php';

        $this->context = new ApiContext(new OAuthTokenCredential($this->credentialsConfig['client_id'], $this->credentialsConfig['client_secret']));

        $this->paymentDefinition = new PaymentDefinition();
        $this->chargeModel = new ChargeModel();
        $this->merchantPreferences = new MerchantPreferences();
        $this->plan = new Plan();
        $this->agreement = new Agreement();
        $this->payer = new Payer();
        $this->shippingAddress = new ShippingAddress();

    }

    protected function dataFormat(array $dados)
    {
        if (!empty($dados)) {
            foreach ($dados as $key => $dado) {
                $dados[$key] = ucfirst(strtolower($dado));
            }
            return $dados;
        }
        throw new \Exception("dados não pode ser vazio");
    }

    protected function arrayDadosVerify(array $dados, $methodCondfg)
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

    protected function changeConfigArray($configuration)
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