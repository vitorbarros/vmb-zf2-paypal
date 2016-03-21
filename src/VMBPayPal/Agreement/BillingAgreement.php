<?php
namespace VMBPayPal\Agreement;

use PayPal\Api\Plan;
use VMBPayPal\AbstractClass\AbstractModel;

class BillingAgreement extends AbstractModel
{

    private $startDate;

    public function __construct()
    {
        parent::__construct();
        $this->startDate = new \DateTime("now");
    }

    public function newAgreement($billinPlanId, $address, $city, $state, $postCode, $countryCode)
    {

        $this->agreement->setName('PR')
            ->setDescription('Pagamento recorrent')
            ->setStartDate($this->startDate->format('Y-m-d H:i:s'));

        $this->payer->setPaymentMethod('paypal');

        $this->shippingAddress->setLine1($address)
            ->setCity($city)
            ->setState($state)
            ->setPostalCode($postCode)
            ->setCountryCode($countryCode);

        $this->agreement->setPayer($this->payer)
            ->setPlan(Plan::get($billinPlanId))
            ->setShippingAddress($this->shippingAddress);

        try{
            $this->agreement->create($this->context);
            return $this->agreement->getApprovalLink();
        }catch(\Exception $e) {
            return $e->getMessage();
        }

    }

}