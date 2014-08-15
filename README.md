# Omnipay: Sisow

**Sisow gateway for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/fruitcakestudio/omnipay-sisow.png?branch=master)](https://travis-ci.org/fruitcakestudio/omnipay-sisow)
[![Latest Stable Version](https://poser.pugx.org/fruitcakestudio/omnipay-sisow/version.png)](https://packagist.org/packages/fruitcakestudio/omnipay-sisow)
[![Total Downloads](https://poser.pugx.org/fruitcakestudio/omnipay-sisow/d/total.png)](https://packagist.org/packages/fruitcakestudio/omnipay-sisow)

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Sisow support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "fruitcakestudio/omnipay-sisow": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Sisow

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository. See also the [Sisow REST Documentation](http://www.sisow.nl/downloads/REST321.pdf)

## Example

```php
 $gateway = \Omnipay\Omnipay::create('Sisow');
    $gateway->initialize(array(
        'shopId' => '',
        'merchantId' => '0123456',
        'merchantKey' => 'b36d8259346eaddb3c03236b37ad3a1d7a67cec6',
        'testMode' => true,
    ));

    // Start the purchase
    if(!isset($_GET['trxid'])){
        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $response = $gateway->purchase(array(
            'amount' => "6.84",
            'description' => "Testorder #1234",
            'issuer' => 99,                         // Get the id from the issuers list, 99 = test issuer
            //'paymentMethod' => 'overboeking',     // For 'overboeking', extra parameters are required:
            'card' => array(
                'email' => 'barry@fruitcakestudio.nl',
                'firstName' => 'Barry',
                'lastName' => 'vd. Heuvel',
                'company' => 'Fruitcake Studio',
            ),
            'transactionId' => 1234,
            'returnUrl' => $url,
            'notifyUrl' => $url,
        ))->send();

        if ($response->isRedirect()) {
            // redirect to offsite payment gateway
            $response->redirect();
        } elseif ($response->isPending()) {
            // Process started (for example, 'overboeking')
            return "Pending, Reference: ". $response->getTransactionReference();
        } else {
            // payment failed: display message to customer
            return "Error " .$response->getCode() . ': ' . $response->getMessage();
        }
    }else{
        // Check the status
        $response = $gateway->completePurchase()->send();
        if($response->isSuccessful()){
            $reference = $response->getTransactionReference();  // TODO; Check the reference/id with your database
            return "Transaction '" . $response->getTransactionId() . "' succeeded!";
        }else{
            return "Error " .$response->getCode() . ': ' . $response->getMessage();
        }
    }
```

**Note, transactionReference is only available in the PurchaseResponse when an `issuer` is set. Use the fetchIssuers response to see the available issuers, or use the [Javascript script](https://www.sisow.nl/Sisow/iDeal/issuers.js) to fill the issuers**

```php
$response = Omnipay::fetchIssuers()->send();
if($response->isSuccessful()){
    print_r($response->getIssuers());
}
```    
    
The billing/shipping data are set with the `card` parameter, with an array or [CreditCard object](https://github.com/omnipay/omnipay#credit-card--payment-form-input).
Other parameters that can be entered with 'overboeking' are:

 - `including` (true/false to include a link to pay with ideal
 - `days` (number of days before a reminder is sent)
        
## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/fruitcakestudio/omnipay-sisow/issues),
or better yet, fork the library and submit a pull request.
