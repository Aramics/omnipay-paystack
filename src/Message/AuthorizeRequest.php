<?php

namespace Omnipay\Paystack\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

class AuthorizeRequest extends AbstractRequest
{
    /**
     * @var string
     */
    static protected $base_url = 'https://api.paystack.co/transaction/';
    protected $endpoint = 'initialize';
    var $secretKey;
    /**
     * @inheritdoc
     */
    public function getData()
    {
        $data =[];
        if(isset($this->getParameters()['transactionReference'])){
            $this->endpoint = 'verify/'.$this->getParameter('transactionReference');
            $data['reference'] = $this->getParameter('transactionReference');
            $data['transactionReference'] = $data['reference'];
        }


        if($this->endpoint == 'initialize'){
            $this->validate('email', 'amount');
        

            $data['email'] = $this->getEmail();
            $data['amount'] = $this->getAmount();
            $data['callback_url'] = $this->getReturnUrl();

        }
        return $data;
    }

    public function getUrl($data){
        $endpoint = isset($data['reference']) ? 'verify/'.$data['reference']:'initialize';
        $this->endpoint = $endpoint;
        return static::$base_url.$endpoint;
    }

    public function getHttpMethod($data)
    {
        if(isset($data['reference']))
            return 'GET';
        return 'POST';
    }

    /**
     * @inheritdoc
     */
    public function sendData($data)
    {
        $headers = [
            'authorization' => 'Bearer ' . $this->getSecret($data),
            'content-type' => 'application/json',
            'cache-control' => 'no-cache',
        ];

        $body = json_encode($data);
        $httpResponse = $this->httpClient->request($this->getHttpMethod($data), $this->getUrl($data), $headers, $body);
        return $this->createResponse($httpResponse->getBody()->getContents(), $httpResponse->getHeaders());

        
    }

    protected function createResponse($data, $headers = [])
    {
        
        return $this->response = new Response($this, $data, $headers);
    }

    /**
     * @param string $value
     *
     * @return AuthorizeRequest
     */
    public function setEmail(string $value): AuthorizeRequest
    {
        return $this->setParameter('email', $value);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->getParameter('email');
    }

    protected function getSecret()
    {
        return $this->secretKey;
    }
    public function setSecretKey($secretKey){
        $this->secretKey = $secretKey;
    }
}
