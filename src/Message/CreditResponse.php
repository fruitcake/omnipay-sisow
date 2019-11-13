<?php

namespace Omnipay\Sisow\Message;

class CreditResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return isset($this->data->creditinvoice->invoiceno);
    }

    /**
     * @return string|null
     */
    public function getInvoiceNumber()
    {
        return isset($this->data->creditinvoice->invoiceno) ?
            (string) $this->data->creditinvoice->invoiceno :
            null;
    }

    /**
     * @return string|null
     */
    public function getDocumentId()
    {
        return isset($this->data->creditinvoice->documentid) ?
            (string) $this->data->creditinvoice->documentid :
            null;
    }
}
