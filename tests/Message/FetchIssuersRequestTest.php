<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Tests\TestCase;

class FetchIssuersRequestTest extends TestCase
{
    /**
     * @var FetchIssuersRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new FetchIssuersRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchIssuersSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());

        $issuers = $response->getIssuers();
        $this->assertEquals(9, count($issuers));

        $issuer = $issuers[0];
        $this->assertInstanceOf('\Omnipay\Common\Issuer', $issuer);
        $this->assertEquals('01', $issuer->getId());
        $this->assertEquals('ABN Amro Bank', $issuer->getName());
    }
}
