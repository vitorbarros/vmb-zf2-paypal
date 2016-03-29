<?php
namespace VMBPayPal;

use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use VMBPayPal\Service\AgreementService;
use VMBPayPal\Service\BillingService;
use VMBPayPal\Service\PayPalPaymentService;

class Module
{
    public function getConfig()
    {

        return include __DIR__ . '../../../config/module.config.php';
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
                    return new BillingService();
                },
                'VMBPayPal\Service\Agreement' => function($sm) {
                    return new AgreementService();
                },
                'VMBPayPal\Service\PayPalPayment' => function($sm) {
                    return new PayPalPaymentService();
                },
            )
        );
    }
}