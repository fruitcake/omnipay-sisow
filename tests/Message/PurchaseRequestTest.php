<?php

namespace Omnipay\Sisow\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $arguments = array($this->getHttpClient(), $this->getHttpRequest());
        $this->request = m::mock('Omnipay\Sisow\Message\PurchaseRequest[getEndpoint]', $arguments);

        $this->request->setShopId('0');
        $this->request->setMerchantId('0123456');
        $this->request->setMerchantKey('b36d8259346eaddb3c03236b37ad3a1d7a67cec6');
        $this->request->setAmount('10.00');
        $this->request->setTransactionId('123');
        $this->request->setReturnUrl('http://localhost/return');
        $this->request->setNotifyUrl('http://localhost/notify');
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.sisow.nl/Sisow/iDeal/Simulator.aspx?merchantid=0123456&txid=TEST080494974182&sha1=ae6c5ca5daac1a3e907f0bb9014bf2c6c2caa0f2', $response->getRedirectUrl());
        $this->assertEquals('TEST080494974282', $response->getTransactionReference());
    }

    public function testSendSuccessNoIssuer()
    {
        $this->setMockHttpResponse('PurchaseSuccessNoIssuer.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.sisow.nl/Sisow/iDeal/RestPay.aspx?id=80494974814&merchantid=0123456&sha1=b3dc1ac353e3983b3e9ba285de1b1f3d774fc8c9', $response->getRedirectUrl());
        $this->assertEquals(null, $response->getTransactionReference());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('PurchaseError.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Merchant not found', $response->getMessage());
        $this->assertEquals('TA3220', $response->getCode());
    }
}
