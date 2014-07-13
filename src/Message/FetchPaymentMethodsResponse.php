<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\FetchPaymentMethodsResponseInterface;
use Omnipay\Common\PaymentMethod;

class FetchPaymentMethodsResponse extends AbstractResponse implements FetchPaymentMethodsResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return isset($this->data->merchant->payments);
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentMethods()
    {
        $methods = array();

        if (isset($this->data->merchant->payments)) {
            foreach ($this->data->merchant->payments->payment as $method) {
                $method = (string)$method;
                $methods[] = new PaymentMethod($method, $method);
            }
        }

        return $methods;
    }
}