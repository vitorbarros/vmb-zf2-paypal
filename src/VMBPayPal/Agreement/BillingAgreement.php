<?php
namespace VMBPayPal\Agreement;

use PayPal\Api\Agreement;

class BillingAgreement extends Agreement
{

    public function __construct($data = null)
    {
        parent::__construct($data);
    }

}