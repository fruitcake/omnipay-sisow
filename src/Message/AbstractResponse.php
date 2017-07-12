<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;
use Omnipay\Common\Message\RequestInterface;

abstract class AbstractResponse extends BaseAbstractResponse
{

    /**
     * @var string
     */
    protected $code;
    
    /**
     * @var string
     */
    protected $message;

    /**
     * {@inheritdoc}
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if (isset($this->data->error)) {
            $this->code = (string) $this->data->error->errorcode;
            $this->message = (string) $this->data->error->errormessage;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        if (!$this->isSuccessful()) {
            return $this->message;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        if (!$this->isSuccessful()) {
            return $this->code;
        }

        return null;
    }
}
