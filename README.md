# Omnipay: Swedbank

**Swedbank driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements Vipps support for Omnipay.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/empatix/omnipay-swedbank.svg?style=flat-square)](https://packagist.org/packages/empatix/omnipay-swedbank)
[![Build Status](https://img.shields.io/travis/empatix/omnipay-swedbank/master.svg?style=flat-square)](https://travis-ci.org/empatix/omnipay-swedbank)
[![Quality Score](https://img.shields.io/scrutinizer/g/empatix/omnipay-swedbank.svg?style=flat-square)](https://scrutinizer-ci.com/g/empatix/omnipay-swedbank)
[![Total Downloads](https://img.shields.io/packagist/dt/empatix/omnipay-swedbank.svg?style=flat-square)](https://packagist.org/packages/empatix/omnipay-swedbank)

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply require `league/omnipay` and `empatix/omnipay-swedbank` with Composer:

```
composer require league/omnipay empatix/omnipay-swedbank
```

## Basic Usage

The following gateways are provided by this package:

* Swedbank Pay (Card payment instrument)

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository and the [Swedbank documentation](https://developer.swedbankpay.com/payment-instruments/card/)

### Initialize gateway, purchase and redirect to Swedbank

```php
use Empatix\OmnipaySwedbank\Gateway;

$gateway = new Gateway();

$gateway->initialize([
    'merchantId' => '',
    'password'   => '',
]);

$response = $gateway->purchase([
    'amount'      => '10.00',
    'currency'    => 'NOK',
    'description' => 'This is a test transaction',
    'returnUrl'   => $fallbackUrl,
    'notifyUrl'   => $callbackPrefix,
])->send();

if ($response->isRedirect()) {
    $response->redirect();
}
```

### Get the transaction details

```php
$response = $gateway->completePurchase(['transactionReference' => $transactionReference])->send();
```

## Out Of Scope

Omnipay does not cover recurring payments or billing agreements, and so those features are not included in this package. Extensions to this gateway are always welcome.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/empatix/omnipay-swedbank/issues),
or better yet, fork the library and submit a pull request.
