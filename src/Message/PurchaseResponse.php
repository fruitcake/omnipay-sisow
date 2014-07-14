<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return is_null($this->getDocumentId()) && is_null($this->code);
    }

    public function isPending()
    {
        return !is_null($this->getDocumentId());
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        return $this->getIssuerUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionReference()
    {
        if (isset($this->data->transaction) && isset($this->data->transaction->trxid)) {
            return (string) $this->data->transaction->trxid;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionId()
    {
        if (isset($this->data->transaction) && isset($this->data->transaction->purchaseid)) {
            return (string) $this->data->transaction->purchaseid;
        }

        return $this->getEntranceCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getIssuerUrl()
    {
        if (isset($this->data->transaction) && isset($this->data->transaction->issuerurl)) {
            return urldecode($this->data->transaction->issuerurl);
        }

        return null;
    }


    public function getEntranceCode()
    {
        if (isset($this->data->transaction) && isset($this->data->transaction->entrancecode)) {
            return (string) $this->data->transaction->entrancecode;
        }

        return null;
    }

    public function getDocumentId()
    {
        if (isset($this->data->transaction) && isset($this->data->transaction->documentid)) {
            return (string) $this->data->transaction->documentid;
        }

        return null;
    }

    public function getDocumentUrl()
    {
        if (isset($this->data->transaction) && isset($this->data->transaction->documenturl)) {
            return urldecode($this->data->transaction->documenturl);
        }

        return null;
    }
}
