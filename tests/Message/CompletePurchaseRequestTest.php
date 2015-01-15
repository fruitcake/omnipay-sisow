<?php

namespace Omnipay\Sisow\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    protected function setUp()
    {
        $request = $this->getHttpRequest();
        $request->query->set('trxid', '1234');

        $arguments = array($this->getHttpClient(), $request);
        $this->request = m::mock('Omnipay\Sisow\Message\CompletePurchaseRequest[getEndpoint]', $arguments);
        $this->request->setShopId('0');
        $this->request->setMerchantId('0123456');
        $this->request->setMerchantKey('b36d8259346eaddb3c03236b37ad3a1d7a67cec6');
    }

    public function testData()
    {
        $data = $this->request->getData();

        $this->assertSame('0', $data['shopid']);
        $this->assertSame('0123456', $data['merchantid']);
        $this->assertSame('b36d8259346eaddb3c03236b37ad3a1d7a67cec6', $data['merchantkey']);
        $this->assertSame('1234', $data['trxid']);
        $this->assertArrayHasKey('sha1', $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CompletePurchaseFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Failure', $response->getMessage());
        $this->assertEquals(null, $response->getCode());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('CompletePurchaseError.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('No transaction', $response->getMessage());
        $this->assertEquals('TA3140', $response->getCode());
    }
}
