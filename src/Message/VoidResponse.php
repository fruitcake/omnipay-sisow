<?php

namespace Omnipay\Sisow\Message;

class VoidResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return isset($this->data->reservation->status) &&
            'Cancelled' === (string) $this->data->reservation->status;
    }
}
