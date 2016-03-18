<?php
namespace VMBPayPal\AbstractClass;

abstract class AbstractBilling
{

    private $config = [];

    private $childConf;

    public function __construct(array $conf)
    {
        $this->childConf = $conf;
        $this->config = $this->getConfig();
        $this->newBillingConfig();
    }

    private function getConfig()
    {
        return include __DIR__ . '/../config/billing.config.php';
    }

    private function newBillingConfig()
    {

        if (!is_array($this->childConf)) {
            throw new \Exception("childConf attribute must be array");
        } else {

            $diff = array_diff_key($this->childConf, $this->config);
            if (!empty($diff)) {
                throw new \Exception("childConf is invalid, see the documentation https://developer.paypal.com/docs/integration/direct/create-billing-plan/");
            }

        }

    }

}