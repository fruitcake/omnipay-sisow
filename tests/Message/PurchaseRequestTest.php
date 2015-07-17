<?php

namespace Omnipay\Sisow\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;
use Omnipay\Common\CreditCard;

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

        $card = new CreditCard($this->getValidCard());
        $card->setBirthday('01-02-2000');

        $this->request->setCard($card);
        $this->request->setShopId('0');
        $this->request->setMerchantId('0123456');
        $this->request->setMerchantKey('b36d8259346eaddb3c03236b37ad3a1d7a67cec6');
        $this->request->setAmount('10.00');
        $this->request->setTransactionId('123');
        $this->request->setReturnUrl('http://localhost/return');
        $this->request->setNotifyUrl('http://localhost/notify');
    }

    public function testKlarna()
    {
        // setup klarna specific setters
        $this->request->setPaymentMethod('klarna');
        $this->request->setMakeInvoice('true');
        $this->request->setMailInvoice('true');
        $this->request->setBillingCountrycode('nl');
        $this->request->setShippingCountrycode('nl');

        $data = $this->request->getData();

        $this->assertSame('true', $data['makeinvoice']);
        $this->assertSame('true', $data['mailinvoice']);
        $this->assertSame('nl', $data['billing_countrycode']);
        $this->assertSame('nl', $data['shipping_countrycode']);

        $this->assertSame('01022000', $data['birthdate']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.sisow.nl/Sisow/iDeal/Simulator.aspx?merchantid=0123456&txid=TEST080494974182&sha1=ae6c5ca5daac1a3e907f0bb9014bf2c6c2caa0f2',
            $response->getRedirectUrl());
        $this->assertEquals('TEST080494974282', $response->getTransactionReference());
    }

    public function testSendSuccessNoIssuer()
    {
        $this->setMockHttpResponse('PurchaseSuccessNoIssuer.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.sisow.nl/Sisow/iDeal/RestPay.aspx?id=80494974814&merchantid=0123456&sha1=b3dc1ac353e3983b3e9ba285de1b1f3d774fc8c9',
            $response->getRedirectUrl());
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
