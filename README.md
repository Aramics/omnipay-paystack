# Omnipay: Paystack

**Paystack driver for the Omnipay PHP payment processing library**

[![Maintainability](https://api.codeclimate.com/v1/badges/0b7329e3c725e30c4344/maintainability)](https://codeclimate.com/github/Aramics/omnipay-paystack/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/0b7329e3c725e30c4344/test_coverage)](https://codeclimate.com/github/Aramics/omnipay-paystack/test_coverage)
[![Style CI](https://styleci.io/repos/121246094/shield)](https://styleci.io/repos/121246094/shield)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Aramics/omnipay-paystack/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Aramics/omnipay-paystack/?branch=master)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements Paystack support for Omnipay. https://paystack.com/
refer to the API docs here: http://developer.paystack.com/
## Install

Via Composer

``` bash
$ composer require Aramics/omnipay-paystack
```

## Basic Usage

### Get the Paystack redirect URL

```php
use Omnipay\Omnipay;

$url = Omnipay::create('Paystack')
    ->setCredentials(
        'your_key', 
        'your_secret'
    )
    ->setCallbackUrl('https://domain.com/paymentdone/confirm')
    ->getUrl(
        'customermail@domain.com',
        'my_reference',
        'description',
        100 //amount
    );
```

### Check transaction status (from the Paystack ipn)

1) Configure & setup an endpoint to receive the ipn message from Paystack
2) Listen for the message and use `getTransactionStatus` (please handle the http GET vars accordingly)

```php
use Omnipay\Omnipay;

$status = Omnipay::create('Paystack')
    ->setCredentials(
        'your_key', 
        'your_secret'
    )
    ->getTransactionStatus(
        $_REQUEST['paystack_notification_type'],
        $_REQUEST['paystack_transaction_tracking_id'],
        $_REQUEST['paystack_merchant_reference']
    );
```
3) `$status` will be either `PENDING`, `COMPLETED`, `FAILED` or `INVALID`. Handle these statuses in your application workflow accordingly.

