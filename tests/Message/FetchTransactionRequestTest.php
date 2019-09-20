<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    /**
     * @var CaptureRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setMerchantId('123');
        $this->request->setMerchantKey('456');
        $this->request->setTransactionId('TEST080249721209');
    }

    public function testGetData()
    {
        self::assertSame(
            [
                'trxid' => 'TEST080249721209',
                'merchantid' => '123',
                'sha1' => sha1('TEST080249721209123456'),
            ],
            $this->request->getData()
        );
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');

        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame('1000', $response->getAmount());
        self::assertSame('Success', $response->getStatus());
        self::assertSame('TEST080249721209', $response->getTransactionId());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('FetchTransactionError.txt');

        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertNull($response->getAmount());
        self::assertNull($response->getStatus());
        self::assertNull($response->getTransactionId());
    }
}
