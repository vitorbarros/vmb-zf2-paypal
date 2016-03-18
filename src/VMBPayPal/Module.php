<?php
namespace VMBPayPal;

use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use VMBPayPal\Service\BillingService;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'VMBPayPal\Service\Billing' => function($sm) {
                    $plan = new Plan();
                    $paymentDefinition = new PaymentDefinition();
                    return new BillingService($plan, $paymentDefinition);
                }
            )
        );
    }
}