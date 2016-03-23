<?php
namespace VMBPayPal\Agreement;

use VMBPayPal\AbstractClass\AbstractModel;

class BillingAgreement extends AbstractModel
{

    private $startDate;

    public function __construct()
    {
        parent::__construct();
        $date = new \DateTime("tomorrow");
        $date = str_replace(" ","T",$date->format('Y-m-d H:i:s')) . 'Z';
        $this->startDate = $date;
    }

    public function newAgreement($billinPlanId, $address, $city, $state, $postCode, $countryCode)
    {

        $dados = $this->dataFormat(array(
            'address' => $address,
            'city' => $city,
        ));

        $this->agreement->setName('PR')
            ->setDescription('Pagamento recorrente')
            ->setStartDate($this->startDate);

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

        try {
            $agreement = $this->agreement->create($this->context);
            return $agreement->getApprovalLink();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function executeAgreement($token)
    {
        try{
            $agreement = $this->agreement->execute($token,$this->context);
            return $agreement->getId();
        }catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAgreementByTransactionId($transacionId, array $param = array())
    {
        if ($transacionId != null) {
           //TODO implementar essa funcionalidade
        }
        throw new \Exception("Transacion Id cannot be null");
    }

}