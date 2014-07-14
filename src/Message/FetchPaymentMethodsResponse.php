<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbastractResponse;
use Omnipay\Common\Message\FetchPaymentMethodsResponseInterface;
use Omnipay\Common\PaymentMethod;

class FetchPaymentMethodsResponse extends BaseAbastractResponse implements FetchPaymentMethodsResponseInterface
{
    protected $names = array(
        'ideal' => 'iDEAL',
        'ecare' => 'ecare',
        'ebill' => 'ebill',
        'overboeking' => 'Overboeking',
        'sofort' => 'DIRECTebanking/SofortBanking',
        'mistercash' => 'MisterCash/BanContact',
        'webshop' => 'WebShop GiftCard',
        'fijncadeau' => 'FijnCadeau',
        'podium' => 'Podium Cadeaukaart',
    );

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
                $method = (string) $method;
                $name = isset($this->names[$method]) ? $this->names[$method] : ucfirst($method);
                $methods[] = new PaymentMethod($method, $name);
            }
        }

        return $methods;
    }
}
