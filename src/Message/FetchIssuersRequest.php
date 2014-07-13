<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

class FetchIssuersRequest extends BaseAbstractRequest
{
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/DirectoryRequest';

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->get($this->endpoint)->send();

        return $this->response = new FetchIssuersResponse($this, $httpResponse->xml());
    }
}