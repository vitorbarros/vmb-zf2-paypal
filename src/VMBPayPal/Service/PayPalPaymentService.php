<?php
namespace VMBPayPal\Service;

use VMBPayPal\DefaultPayment\PaypalPayment as Payment;

class PayPalPaymentService extends Payment
{
    public function __construct()
    {
        parent::__construct();
    }
}