<?php

namespace Omnipay\Sisow\Message;

class FetchTransactionResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return isset($this->data->transaction->status);
    }

    /**
     * @return string|null Amount in cents
     */
    public function getAmount()
    {
        return isset($this->data->transaction->amount) ?
            (string) $this->data->transaction->amount :
            null;
    }

    /**
     * @return string|null
     */
    public function getStatus()
    {
        return isset($this->data->transaction->status) ?
            (string) $this->data->transaction->status :
            null;
    }

    /**
     * @inheritDoc
     */
    public function getTransactionId()
    {
        return isset($this->data->transaction->trxid) ?
            (string) $this->data->transaction->trxid :
            null;
    }
}
