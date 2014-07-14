<?php

namespace Omnipay\Sisow\Message;

class CompletePurchaseRequest extends PurchaseRequest
{
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/StatusRequest';
    
    /**
     * {@inheritdoc}
     */
    protected function generateSignature()
    {
        return sha1(
            $this->getTransactionReference() . $this->getMerchantId() . $this->getMerchantKey()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('merchantId', 'merchantKey');

        $data = array(
            'shopid'        => $this->getShopId(),
            'merchantid'    => $this->getMerchantId(),
            'merchantkey'   => $this->getMerchantKey(),
            'trxid'         => $this->getTransactionReference(),
            'sha1'          => $this->generateSignature(),
        );

        return $data;
    }

    public function getTransactionReference()
    {
        return $this->httpRequest->query->get('trxid');
    }
    
    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        if ($data['trxid']) {
            $httpResponse = $this->httpClient->post($this->endpoint, null, $data)->send();
            return $this->response = new CompletePurchaseResponse($this, $httpResponse->xml());
        } else {
            $data = array('transaction' => (object) $this->httpRequest->query->all());
            return $this->response = new CompletePurchaseResponse($this, (object) $data);
        }
    }
}
