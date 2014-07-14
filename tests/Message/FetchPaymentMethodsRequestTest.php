<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Tests\TestCase;

class FetchPaymentMethodsRequestTest extends TestCase
{
    /**
     * @var FetchIssuersRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new FetchPaymentMethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setMerchantId('0123456');
        $this->request->setMerchantKey('b36d8259346eaddb3c03236b37ad3a1d7a67cec6');
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchPaymentMethodsSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());

        $methods = $response->getPaymentMethods();
        $this->assertEquals(4, count($methods));

        $method = $methods[0];
        $this->assertInstanceOf('\Omnipay\Common\PaymentMethod', $method);
        $this->assertEquals('ideal', $method->getId());
        $this->assertEquals('iDEAL', $method->getName());
    }
}
