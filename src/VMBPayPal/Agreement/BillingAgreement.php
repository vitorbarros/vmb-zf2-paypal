<?php
namespace VMBPayPal\Agreement;

use VMBPayPal\AbstractClass\AbstractModel;

class BillingAgreement extends AbstractModel
{

    private $startDate;

    public function __construct()
    {
        parent::__construct();
        $this->startDate = new \DateTime("now");
    }

    public function newAgreement()
    {

        echo '<pre>';
        print_r($this->startDate);
        exit;

        $this->agreement->setName('PR')
            ->setDescription('Pagamento recorrent')
            ->setStartDate($this->startDate);

    }

}