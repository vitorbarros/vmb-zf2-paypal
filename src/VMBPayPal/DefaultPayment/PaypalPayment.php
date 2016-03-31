<?php
namespace VMBPayPal\DefaultPayment;

use PayPal\Api\Details;
use PayPal\Api\Payment;
use VMBPayPal\AbstractClass\AbstractModel;

class PaypalPayment extends AbstractModel
{

    public function __construct()
    {
        parent::__construct();
    }

    private $itensArray = array();
    private $total = 0;

    /**
     * @param array $itens
     * @param array $shipping
     * @param $currency
     * @param $paymentDescription
     * @param array $redirectUrls
     * @return \PayPal\Api\Payment
     * @throws \Exception
     */
    public function newPayment(array $itens, array $shipping, $currency, $paymentDescription, array $redirectUrls)
    {
        $shippingInformation = null;

        if (!empty($itens)) {

            $this->payer->setPaymentMethod('paypal');

            foreach ($itens as $item) {
                try {
                    $this->paypalPaymentVerify($item);

                    $this->item->setName($item['name'])
                        ->setCurrency($item['currency'])
                        ->setQuantity($item['quantity'])
                        ->setSku($item['sku'])
                        ->setPrice($item['price']);

                    $this->itensArray[] = $this->item;
                    $this->total += $item['price'];

                } catch (\Exception $e) {
                    throw $e;
                }
            }

            try {

                $this->itemList->setItems($this->itensArray);

                $shippingInformation = $this->newShipping(array(
                    'shipping' => $shipping['shipping'],
                    'tax' => $shipping['tax'],
                    'subtotal' => $this->total,
                ));
                $this->total += $shipping['tax'];
                $this->total += $shipping['shipping'];

                $this->amount->setCurrency($currency)
                    ->setTotal($this->total)
                    ->setDetails($shippingInformation);

                $this->transaction->setAmount($this->amount)
                    ->setItemList($this->itemList)
                    ->setDescription($paymentDescription)
                    ->setInvoiceNumber(uniqid());

                $this->redirectUrl->setReturnUrl($redirectUrls['return'])
                    ->setCancelUrl($redirectUrls['cancel']);

                $this->payment->setIntent('sale')
                    ->setPayer($this->payer)
                    ->setRedirectUrls($this->redirectUrl)
                    ->setTransactions(array($this->transaction));

                $this->payment->create($this->context);
                return $this->payment->getApprovalLink();

            } catch (\Exception $e) {
                throw $e;
            }
        }
        throw new \Exception("Array itens cannot be empty");
    }

    public function executePayment($paymentId, $payerId)
    {

        if ($paymentId && $payerId) {
            try {
                $payment = Payment::get($paymentId, $this->context);
                echo '<pre>';
                print_r($payment);
                exit;
                $this->paymentExecution->setPayerId($payerId);


            } catch (\Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }
        throw new \Exception("Payment Id and Payer id cannot be null");
    }

    public function newShipping(array $shipping)
    {
        $detaisl = new Details();
        $detaisl->setShipping($shipping['shipping'])
            ->setTax($shipping['tax'])
            ->setSubtotal($shipping['subtotal']);

        return $detaisl;
    }

}