<?php

namespace Omnipay\Sisow\Message;

/**
 * Sisow Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/TransactionRequest';
    
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
            $this->getTransactionId() . $this->getEntranceCode() . $this->getAmountInteger() . $this->getMerchantId() . $this->getMerchantKey()
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

        $data = array(
            'merchantid'    => $this->getMerchantId(),
            'merchantkey'   => $this->getMerchantKey(),
            'payment'       => $this->getPaymentMethod(),
            'purchaseid'    => $this->getTransactionId(),
            'amount'        => $this->getAmountInteger(),
            'issuerid'      => $this->getIssuer(),
            'entrancecode'  => $this->getEntranceCode(),
            'description'   => $this->getDescription(),
            'returnurl'     => $this->getReturnUrl(),
            'cancelurl'     => $this->getCancelUrl(),
            'notifyurl'     => $this->getNotifyUrl(),
            'sha1'          => $this->generateSignature(),
            'testmode'      => $this->getTestMode(),
        );

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
