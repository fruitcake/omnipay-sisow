<?php

namespace Omnipay\Sisow;

use Omnipay\Common\AbstractGateway;
use Omnipay\Sisow\Message\CaptureRequest;
use Omnipay\Sisow\Message\CreditRequest;
use Omnipay\Sisow\Message\FetchTransactionRequest;
use Omnipay\Sisow\Message\RefundRequest;
use Omnipay\Sisow\Message\VoidRequest;

/**
 * Sisow gateway.
 */
class Gateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Sisow';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return array(
            'shopId' => '',
            'merchantId' => '',
        );
    }
    
    public function getMerchantKey()
    {
        return $this->getParameter('merchantKey');
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('merchantKey', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
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
     * Retrieve iDEAL issuers.
     *
     * @param array $parameters An array of options
     *
     * @return \Omnipay\Sisow\Message\FetchIssuersRequest
     */
    public function fetchIssuers(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Sisow\Message\FetchIssuersRequest', $parameters);
    }
    
    /**
     * Retrieve the payment methods.
     *
     * @param array $parameters An array of options
     *
     * @return \Omnipay\Sisow\Message\FetchPaymentMethodsRequest
     */
    public function fetchPaymentMethods(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Sisow\Message\FetchPaymentMethodsRequest', $parameters);
    }

    /**
     * Start a purchase request.
     *
     * @param array $parameters An array of options
     *
     * @return \Omnipay\Sisow\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Sisow\Message\PurchaseRequest', $parameters);
    }

    /**
     * Complete a purchase.
     *
     * @param array $parameters An array of options
     *
     * @return \Omnipay\Sisow\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Sisow\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * @param mixed[] $parameters
     *
     * @return CaptureRequest
     */
    public function capture(array $parameters = [])
    {
        return $this->createRequest(CaptureRequest::class, $parameters);
    }

    /**
     * @param mixed[] $parameters
     *
     * @return VoidRequest
     */
    public function void(array $parameters = [])
    {
        return $this->createRequest(VoidRequest::class, $parameters);
    }

    /**
     * @param mixed[] $parameters
     *
     * @return CreditRequest
     */
    public function credit(array $parameters = [])
    {
        return $this->createRequest(CreditRequest::class, $parameters);
    }

    /**
     * @param mixed[] $parameters
     *
     * @return RefundRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * @param mixed[] $parameters
     *
     * @return FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $parameters);
    }
}
