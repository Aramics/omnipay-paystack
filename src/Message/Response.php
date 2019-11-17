<?php

/**
 * Stripe Response.
 */
namespace Omnipay\Paystack\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Stripe Response.
 *
 * This is the response class for all Stripe requests.
 *
 * @see \Omnipay\Stripe\Gateway
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Request id
     *
     * @var string URL
     */
    protected $requestId = null;

    /**
     * @var array
     */
    protected $headers = [];

    public function __construct(RequestInterface $request, $data, $headers = [])
    {
        $this->request = $request;
        $this->data = json_decode($data, true);
        $this->headers = $headers;
    }

    /**
     * Is the transaction successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        if ($this->isRedirect()) {
            return false;
        }

        return !isset($this->data['error']) && $this->data['status'] && isset($this->data['data']) && $this->data['data']['status'] =="success";
    }

    

    /**
     * Get the transaction reference.
     *
     * @return string|null
     */
    public function getTransactionReference()
    {
        if (isset($this->data['data']['reference'])) {
            return $this->data['data']['reference'];
        }
        

        return null;
    }

    /**
     * Get a token, for createCard requests.
     *
     * @return string|null
     */
    public function getToken()
    {
        if (isset($this->data['data']['access_code'])) {
            return $this->data['data']['access_code'];
        }

        return null;
    }



    /**
     * Get the error message from the response.
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getMessage()
    {
        if (isset($this->data['message'])) {
            return $this->data['message'];
        }

        return null;
    }

    /**
     * Get the error message from the response.
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->getToken();
    }


    /**
     * @return bool
     */
    public function isRedirect()
    {
        if (isset($this->data['data']['authorization_url'])) {
            
                return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getRedirectUrl()
    {
        if (isset($this->data['data']['authorization_url'])) {
            return $this->data['data']['authorization_url'];
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * @return mixed
     */
    public function getRedirectData()
    {
        return null;
    }

 
}
