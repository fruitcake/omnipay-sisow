<?php

namespace Omnipay\Sisow\Message;

use \Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * Sisow Abstract Request
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    /**
     * Generate the message signature
     *
     * @return string|null
     */
    protected function generateSignature()
    {
        return null;
    }
    
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantKey()
    {
        return $this->getParameter('merchantKey');
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('merchantKey', $value);
    }

    public function getShopId()
    {
        return $this->getParameter('shopId');
    }

    public function setShopId($value)
    {
        return $this->setParameter('shopId', $value);
    }

    /**
     * @param ResponseInterface $response
     * @return \SimpleXMLElement
     */
    protected function parseXmlResponse(ResponseInterface $response)
    {
        return simplexml_load_string($response->getBody()->getContents());
    }
}
