<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class CompletePurchaseResponse extends PurchaseResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return is_null($this->code);
    }


   
}