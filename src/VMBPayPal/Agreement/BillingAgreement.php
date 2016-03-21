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

    public function newAgreement($billinPlanId, $address, $city, $state, $postCode, $countryCode)
    {

        $dados = $this->dataFormat(array(
            'address' => $address,
            'city' => $city,
        ));

        $this->agreement->setName('PR')
            ->setDescription('Pagamento recorrente')
            ->setStartDate('2019-06-17T9:45:04Z');

        $this->plan->setId($billinPlanId);
        $this->agreement->setPlan($this->plan);

        $this->payer->setPaymentMethod('paypal');
        $this->agreement->setPayer($this->payer);

        $this->shippingAddress->setLine1($dados['address'])
            ->setCity($dados['city'])
            ->setState($state)
            ->setPostalCode($postCode)
            ->setCountryCode($countryCode);
        $this->agreement->setShippingAddress($this->shippingAddress);

        try{
            $agreement = $this->agreement->create($this->context);
            return $agreement->getApprovalLink();
        }catch(\Exception $e) {
            return $e->getMessage();
        }

    }

}