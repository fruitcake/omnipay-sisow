<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Http\ResponseParser;
use Psr\Http\Message\ResponseInterface;

class CompletePurchaseRequest extends PurchaseRequest
{
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/StatusRequest';
    
    /**
     * {@inheritdoc}
     */
    protected function generateSignature()
    {
        return sha1(
            $this->getTransactionReference() . $this->getShopId() .
            $this->getMerchantId() . $this->getMerchantKey()
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
            $httpResponse = $this->httpClient->request(
                'POST',
                $this->endpoint,
                [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                http_build_query($data)
            );
            return $this->response = new CompletePurchaseResponse($this, $this->parseXmlResponse($httpResponse));
        } else {
            $data = array('transaction' => (object) $this->httpRequest->query->all());
            return $this->response = new CompletePurchaseResponse($this, (object) $data);
        }
    }
}
