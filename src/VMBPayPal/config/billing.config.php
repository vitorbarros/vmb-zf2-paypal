<?php
namespace VMBPayPal\config;

return array(
    'name' => '',
    'description' => '',
    'type' => '',
    'payment_definitions' => array(
        array(
            'name' => '',
            'type' => '',
            'frequency_interval' => '',
            'frequency' => '',
            'cycles' => '',
            'amount' => array(
                'currency' => '',
                'value' => '',
            ),
            'charge_models' => array(
                array(
                    'type' => '',
                    'amount' => array(
                        'currency' => '',
                        'value' => ''
                    )
                ),
                array(
                    'type' => '',
                    'amount' => array(
                        'currency' => '',
                        'value' => ''
                    )
                )
            )
        ),
    ),
    'merchant_preferences' => array(
        'setup_fee' => array(
            'currency' => '',
            'value' => ''
        ),
        'cancel_url' => '',
        'return_url' => '',
        'max_fail_attempts' => '',
        'auto_bill_amount' => '',
        'initial_fail_amount_action' => ''
    )
);