<?php
namespace VMBPayPal\DefaultPayment;

use PayPal\Api\Details;
use PayPal\Api\Payment;
use VMBPayPal\AbstractClass\AbstractModel;

use PayPal\Api\Amount;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

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

    public function executePayment($paymentId, $payerId, array $shipping = array(), $currency, $total)
    {

        if ($paymentId && $payerId) {
            try {

                $payment = Payment::get($paymentId, $this->context);

                $execution = new PaymentExecution();
                $execution->setPayerId($payerId);

                $transaction = new Transaction();
                $amount = new Amount();
                $details = new Details();

                $details->setShipping(2.2)
                    ->setTax(1.3)
                    ->setSubtotal(17.50);

                $amount->setCurrency($currency);
                $amount->setTotal(21);
                $amount->setDetails($details);
                $transaction->setAmount($amount);

                $execution->addTransaction($transaction);
                $payment->execute($execution, $this->context);

                return Payment::get($paymentId, $this->context);

//                $payment = Payment::get($paymentId, $this->context);
//                $this->paymentExecution->setPayerId($payerId);
//
//                if (!empty($shipping)) {
//                    $this->details->setShipping($shipping['shipping'])
//                        ->setTax($shipping['tax'])
//                        ->setSubtotal($shipping['subtotal']);
//
//                    $this->amount->setCurrency($currency)
//                        ->setTotal($total)
//                        ->setDetails($this->details);
//                }else{
//                    $this->amount->setCurrency($currency)
//                        ->setTotal($total)
//                        ->setDetails(null);
//                }
//
//                $this->transaction->setAmount($this->amount);
//                $this->paymentExecution->addTransaction($this->transaction);
//
//                $payment->execute($this->paymentExecution, $this->context);
//                return Payment::get($paymentId, $this->context);

            } catch (\Exception $e) {
                throw $e;
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