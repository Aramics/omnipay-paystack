# Omnipay: Paystack

**Paystack driver for the Omnipay PHP payment processing library**

[![Maintainability](https://api.codeclimate.com/v1/badges/0b7329e3c725e30c4344/maintainability)](https://codeclimate.com/github/Aramics/omnipay-paystack/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/0b7329e3c725e30c4344/test_coverage)](https://codeclimate.com/github/Aramics/omnipay-paystack/test_coverage)
[![Style CI](https://styleci.io/repos/121246094/shield)](https://styleci.io/repos/121246094/shield)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements Paystack support for Omnipay. https://paystack.com/
refer to the API docs here: http://developer.paystack.com/
## Install

Via Composer

``` bash
$ composer require aramics/omnipay-paystack
```

## Basic Usage

### Get the Paystack redirect URL

```php
use Omnipay\Omnipay;

$pay = Omnipay::create('Paystack')
    ->setSecretKey('YOUR_SECRET_KEY');
    ->purchase([
            'amount' => 2000,
            'transactionId' => 'transId',
            'currency' => 'NGN',
            'cancelUrl' => 'https://canclecallback',
            'returnUrl' => 'https://yourcallback',
        ]);
if ($pay->isRedirect()) { 
   $pay->redirect(); //redirect to pay on paystack
}
```

### Check transaction status (from the Paystack ipn)

1) Configure & setup an endpoint to receive the ipn message from Paystack
2) Listen for the message and use `getTransactionStatus` (please handle the http GET vars accordingly)

```php
use Omnipay\Omnipay;

$status = Omnipay::create('Paystack')
    ->setSecretKey('YOUR_SECRET_KEY');
    ->completePurchase([
                'transactionId' => 'transId',
            ])
      ->send();
if ($status->isSuccessful()) {
    //give value to user
}
```
3) `$status` will be either paystack verify transaction object . Handle these statuses in your application workflow accordingly.

