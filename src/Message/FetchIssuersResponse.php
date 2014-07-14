<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;
use Omnipay\Common\Message\FetchIssuersResponseInterface;
use Omnipay\Common\Issuer;

class FetchIssuersResponse extends BaseAbstractResponse implements FetchIssuersResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return isset($this->data->directory);
    }

    /**
     * {@inheritdoc}
     */
    public function getIssuers()
    {
        $issuers = array();

        if (isset($this->data->directory)) {
            foreach ($this->data->directory->issuer as $issuer) {
                $issuers[] = new Issuer((string) $issuer->issuerid, (string) $issuer->issuername);
            }
        }

        return $issuers;
    }
}
