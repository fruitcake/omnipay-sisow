<?php

namespace Omnipay\Sisow\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Sisow Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/TransactionRequest';

    public function getDays()
    {
        return $this->getParameter('days');
    }

    public function setDays($value)
    {
        return $this->setParameter('days', $value);
    }

    public function getIncluding()
    {
        return $this->getParameter('including');
    }

    public function setIncluding($value)
    {
        return $this->setParameter('including', $value);
    }

    public function getEntranceCode()
    {
        return $this->getParameter('entranceCode') ?: $this->getTransactionId();
    }

    public function setEntranceCode($value)
    {
        return $this->setParameter('entranceCode', $value);
    }

    public function getMakeInvoice()
    {
        return $this->getParameter('makeInvoice');
    }

    public function setMakeInvoice($value)
    {
        return $this->setParameter('makeInvoice', $value);
    }

    public function getMailInvoice()
    {
        return $this->getParameter('mailInvoice');
    }

    public function setMailInvoice($value)
    {
        return $this->setParameter('mailInvoice', $value);
    }

    public function getBillingCountrycode()
    {
        return $this->getParameter('billingCountrycode');
    }

    public function setBillingCountrycode($value)
    {
        return $this->setParameter('billingCountrycode', $value);
    }

    public function getShippingCountrycode()
    {
        return $this->getParameter('shippingCountrycode');
    }

    public function setShippingCountrycode($value)
    {
        return $this->setParameter('shippingCountrycode', $value);
    }

    /**
     * {@inheritdoc}
     */
    protected function generateSignature()
    {
        return sha1(
            $this->getTransactionId() . $this->getEntranceCode() . $this->getAmountInteger() .
            $this->getShopId() . $this->getMerchantId() . $this->getMerchantKey()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'transactionId',
            'returnUrl',
            'notifyUrl'
        );

        if (!$this->getTestMode() && $this->getIssuer() == 99) {
            throw new InvalidRequestException("The issuer can only be '99' in testMode!");
        }

        $data = array(
            'shopid' => $this->getShopId(),
            'merchantid' => $this->getMerchantId(),
            'merchantkey' => $this->getMerchantKey(),
            'payment' => $this->getPaymentMethod(),
            'purchaseid' => $this->getTransactionId(),
            'amount' => $this->getAmountInteger(),
            'issuerid' => $this->getIssuer(),
            'entrancecode' => $this->getEntranceCode(),
            'description' => $this->getDescription(),
            'including' => $this->getIncluding(),
            'days' => $this->getDays(),
            'returnurl' => $this->getReturnUrl(),
            'cancelurl' => $this->getCancelUrl(),
            'notifyurl' => $this->getNotifyUrl(),
            'sha1' => $this->generateSignature(),
            'testmode' => $this->getTestMode(),
        );

        /** @var \Omnipay\Common\CreditCard $card */
        $card = $this->getCard();
        if ($card) {
            if ($this->getPaymentMethod() == 'overboeking' || $this->getPaymentMethod() == 'klarna') {
                $data['billing_mail'] = $card->getEmail();
                $data['billing_firstname'] = $card->getBillingFirstName();
                $data['billing_lastname'] = $card->getBillingLastName();
            }

            if ($this->getPaymentMethod() == 'klarna') {
                $data['billing_company'] = $card->getBillingCompany();
                $data['billing_address1'] = $card->getBillingAddress1();
                $data['billing_address2'] = $card->getBillingAddress2();
                $data['billing_zip'] = $card->getBillingPostcode();
                $data['billing_city'] = $card->getBillingCity();
                $data['billing_country'] = $card->getBillingCountry();
                $data['billing_phone'] = $card->getBillingPhone();
                $data['birthdate'] = date('dmY', strtotime($card->getBirthday()));
                if ($this->getMakeInvoice()) {
                    $data['makeinvoice'] = $this->getMakeInvoice();
                }
                if ($this->getMailInvoice()) {
                    $data['mailinvoice'] = $this->getMailInvoice();
                }
                $data['billing_countrycode'] = $this->getBillingCountrycode();
                $data['shipping_countrycode'] = $this->getShippingCountrycode();

                // only used for klarna account (required for klarna invoice as -1)
                $data['pclass'] = - 1;

                $data = array_merge($data, $this->getItemData());
            }

            $data['shipping_mail'] = $card->getEmail();
            $data['shipping_firstname'] = $card->getShippingFirstName();
            $data['shipping_lastname'] = $card->getShippingLastName();
            $data['shipping_company'] = $card->getShippingCompany();
            $data['shipping_address1'] = $card->getShippingAddress1();
            $data['shipping_address2'] = $card->getShippingAddress2();
            $data['shipping_zip'] = $card->getShippingPostcode();
            $data['shipping_city'] = $card->getShippingCity();
            $data['shipping_country'] = $card->getShippingCountry();
            $data['shipping_phone'] = $card->getShippingPhone();
        }

        return $data;
    }

    protected function getItemData()
    {
        $data = array();
        $items = $this->getItems();

        if ($items) {
            foreach ($items as $i => $item) {
                $x = $i + 1;
                $data['product_id_' . $x] = $item->getName();
                $data['product_description_' . $x] = $item->getDescription();
                $data['product_quantity_' . $x] = $item->getQuantity();
                $data['product_netprice_' . $x] = round(($this->formatCurrency($item->getPrice()) / 121 * 100) * 100);
                $data['product_total_' . $x] = round(
                    $this->formatCurrency($item->getPrice()) * $item->getQuantity() * 100
                );
                $data['product_nettotal_' . $x] = round(
                    ($this->formatCurrency($item->getPrice()) / 121 * 100) * $item->getQuantity() * 100
                );

                //@todo fix tax rates
                $data['product_tax_' . $x] = round(($this->formatCurrency($item->getPrice()) / 121 * 21) * 100);
                $data['product_taxrate_' . $x] = 21 * 100;
            }
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->endpoint, null, $data)->send();

        return $this->response = new PurchaseResponse($this, $httpResponse->xml());
    }
}
