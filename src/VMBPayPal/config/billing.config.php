<?php
namespace VMBPayPal\config;

return array(
    'newBillingPlan' => array(
        'name' => '',
        'description' => '',
        'type' => '',
        'definition' => array(
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
                )
            )
        ),
        'merchantPreferences' => array(
            'returnUrl' => '',
            'cancelUrl' => '',
            'autoBillAmount' => '',
            'initialFailAmountAction' => '',
            'maxFailAttempts' => ''
        )
    )
);