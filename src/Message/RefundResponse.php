<?php

namespace Omnipay\Sisow\Message;

class RefundResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return isset($this->data->refund->refundid);
    }

    /**
     * @return string|null
     */
    public function getRefundId()
    {
        return isset($this->data->refund->refundid) ?
            (string) $this->data->refund->refundid :
            null;
    }
}
