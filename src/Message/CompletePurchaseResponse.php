<?php

namespace Omnipay\Sisow\Message;

class CompletePurchaseResponse extends PurchaseResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        if (isset($this->data->transaction) && isset($this->data->transaction->status)) {
            if ((string) $this->data->transaction->status == 'Success') {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        if (isset($this->data->transaction) && isset($this->data->transaction->status)) {
            return (string) $this->data->transaction->status == 'Success';
        } elseif (!is_null($this->code)) {
            return $this->data;
        }

        return null;
    }
}
