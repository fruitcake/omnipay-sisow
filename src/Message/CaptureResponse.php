<?php
declare(strict_types=1);

namespace Omnipay\Sisow\Message;

class CaptureResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return isset($this->data->invoice->invoiceno);
    }

    /**
     * @return string|null
     */
    public function getInvoiceNumber()
    {
        return isset($this->data->invoice->invoiceno) ?
            (string) $this->data->invoice->invoiceno :
            null;
    }

    /**
     * @return string|null
     */
    public function getDocumentId()
    {
        return isset($this->data->invoice->documentid) ?
            (string) $this->data->invoice->documentid :
            null;
    }
}
