<?php

namespace Omnipay\Sisow\Message;

class FetchPaymentMethodsRequest extends AbstractRequest
{
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/CheckMerchantRequest';

    /**
     *  Generate a signature
     */
    protected function generateSignature()
    {
        return sha1(
            $this->getMerchantId() .
            $this->getMerchantKey()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('merchantId', 'merchantKey');

        $data = array();
        $data['merchantid'] = $this->getMerchantId();
        $data['merchantkey'] = $this->getMerchantKey();
        $data['sha1'] = $this->generateSignature();

        return $data;
    }
    
    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->endpoint, null, $data)->send();

        return $this->response = new FetchPaymentMethodsResponse($this, $httpResponse->xml());
    }
}
