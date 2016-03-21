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

}