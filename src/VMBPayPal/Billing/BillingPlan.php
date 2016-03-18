<?php
namespace VMBPayPal\Billing;

use VMBPayPal\AbstractClass\AbstractBilling;
use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use Zend\Json\Json;

class BillingPlan extends AbstractBilling
{

    private $conf;
    private $adapter;

    public function __construct(array $conf)
    {
        $this->conf = $conf;
        $this->adapter = new Curl();
    }

    public function newBillingPlan($url)
    {

        $config = array(
            'adapter' => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_POSTFIELDS => Json::encode($this->conf)
            ),
        );

        return new Client($url, $config);

    }

}