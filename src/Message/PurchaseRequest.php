<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Sisow Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/TransactionRequest';

    public function getDays()
    {
        return $this->getParameter('days');
    }

    public function setDays($value)
    {
        return $this->setParameter('days', $value);
    }

    public function getIncluding()
    {
        return $this->getParameter('including');
    }

    public function setIncluding($value)
    {
        return $this->setParameter('including', $value);
    }

    public function getEntranceCode()
    {
        return $this->getParameter('entranceCode') ?: $this->getTransactionId();
    }

    public function setEntranceCode($value)
    {
        return $this->setParameter('entranceCode', $value);
    }

    /**
     * {@inheritdoc}
     */
    protected function generateSignature()
    {
        return sha1(
            $this->getTransactionId() . $this->getEntranceCode() .
            $this->getAmountInteger() . $this->getMerchantId() . $this->getMerchantKey()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'transactionId',
            'returnUrl',
            'notifyUrl'
        );

        if (!$this->getTestMode() && $this->getIssuer() == 99) {
            throw new InvalidRequestException("The issuer can only be '99' in testMode!");
        }

        $data = array(
            'shopid'        => $this->getShopId(),
            'merchantid'    => $this->getMerchantId(),
            'merchantkey'   => $this->getMerchantKey(),
            'payment'       => $this->getPaymentMethod(),
            'purchaseid'    => $this->getTransactionId(),
            'amount'        => $this->getAmountInteger(),
            'issuerid'      => $this->getIssuer(),
            'entrancecode'  => $this->getEntranceCode(),
            'description'   => $this->getDescription(),
            'including'     => $this->getIncluding(),
            'days'          => $this->getDays(),
            'returnurl'     => $this->getReturnUrl(),
            'cancelurl'     => $this->getCancelUrl(),
            'notifyurl'     => $this->getNotifyUrl(),
            'sha1'          => $this->generateSignature(),
            'testmode'      => $this->getTestMode(),
        );

        /** @var \Omnipay\Common\CreditCard $card */
        $card = $this->getCard();
        if ($card) {
            if ($this->getPaymentMethod() == 'overboeking' || $this->getPaymentMethod() == 'ecare') {
                $data['billing_mail']       = $card->getEmail();
                $data['billing_firstname']  = $card->getBillingFirstName();
                $data['billing_lastname']   = $card->getBillingLastName();
            }

            if ($this->getPaymentMethod() == 'ecare') {
                $data['billing_company']    = $card->getBillingCompany();
                $data['billing_address1']   = $card->getBillingAddress1();
                $data['billing_address2']   = $card->getBillingAddress2();
                $data['billing_zip']        = $card->getBillingPostcode();
                $data['billing_city']       = $card->getBillingCity();
                $data['billing_country']    = $card->getBillingCountry();
                $data['billing_phone']      = $card->getBillingPhone();
            }

            if ($this->getPaymentMethod() == 'esend') {
                $data['shipping_mail']       = $card->getEmail();
                $data['shipping_firstname']  = $card->getShippingFirstName();
                $data['shipping_lastname']   = $card->getShippingLastName();
                $data['shipping_company']    = $card->getShippingCompany();
                $data['shipping_address1']   = $card->getShippingAddress1();
                $data['shipping_address2']   = $card->getShippingAddress2();
                $data['shipping_zip']        = $card->getShippingPostcode();
                $data['shipping_city']       = $card->getShippingCity();
                $data['shipping_country']    = $card->getShippingCountry();
                $data['shipping_phone']      = $card->getShippingPhone();
            }
        }

        return $data;
    }
    
    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->endpoint, null, $data)->send();

        return $this->response = new PurchaseResponse($this, $httpResponse->xml());
    }
}
