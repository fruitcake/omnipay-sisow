<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    /**
     * @var CaptureRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setMerchantId('123');
        $this->request->setMerchantKey('456');
        $this->request->setTransactionId('789');
    }

    public function testGetData()
    {
        self::assertSame(
            [
                'trxid' => '789',
                'merchantid' => '123',
                'sha1' => sha1('789123456'),
            ],
            $this->request->getData()
        );
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');

        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('123456', $response->getRefundId());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('RefundError.txt');

        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertNull($response->getRefundId());
    }
}
