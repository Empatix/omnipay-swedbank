<?php

namespace Empatix\OmnipaySwedbank\Messages;

use Empatix\OmnipaySwedbank\Gateway;
use Omnipay\Common\Message\AbstractRequest;
use Empatix\OmnipaySwedbank\Messages\Response;

class PurchaseRequest extends AbstractRequest
{
    protected $resource = '/psp/creditcard/payments';
    protected $productionEndpoint = 'https://api.payex.com';
    protected $testEndpoint = 'https://api.externalintegration.payex.com';

    public function setPassword($value)
    {
        $this->setParameter('password', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setLanguage($value)
    {
        $this->setParameter('language', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setMetaData($value)
    {
        $this->setParameter('metadata', $value);
    }

    public function getMetaData()
    {
        return $this->getParameter('metadata');
    }

    public function setAfterUrl($value)
    {
        $this->setParameter('afterUrl', $value);
    }

    public function getAfterUrl()
    {
        return $this->getParameter('afterUrl');
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setVatAmount($value)
    {
        $this->setParameter('vatAmount', $value);
    }

    public function getVatAmount()
    {
        return $this->getParameter('vatAmount');
    }

    public function setUserAgent($value)
    {
        $this->setParameter('userAgent', $value);
    }

    public function getUserAgent()
    {
        return $this->getParameter('userAgent');
    }

    public function setPayeeId($value)
    {
        $this->setParameter('payeeId', $value);
    }

    public function getPayeeId()
    {
        return $this->getParameter('payeeId');
    }

    public function setPayeeReference($value)
    {
        $this->setParameter('payeeReference', $value);
    }

    public function getPayeeReference()
    {
        return $this->getParameter('payeeReference');
    }

    public function sendData($data)
    {
        $result = $this->httpClient->request(
            'POST',
            $this->getEndpoint() . $this->resource,
            [
                'Authorization' => 'Bearer ' . $this->getPassword(),
                'Content-Type' => 'application/json; charset=utf-8',
                'Accept' => 'application/problem+json; q=1.0, application/json; q=0.9',
            ],
            json_encode($data)
        );

        return $this->response = new Response(
            $this,
            json_decode($result->getBody()->getContents(), true)
        );
    }

    public function getData()
    {
        return [
            'payment' => [
                'operation' => 'Purchase',
                'intent' => 'AutoCapture',
                'currency' => $this->getCurrency(),
                'prices' => [
                    [
                        'type' => 'CreditCard',
                        'vatAmount' => $this->getVatAmount(),
                        'amount' => $this->getAmountInteger(),
                    ]
                ],
                'language' =>  $this->getLanguage(),
                'userAgent' => $this->getUserAgent(),
                'description' => $this->getDescription(),
                'urls' => [
                    'cancelUrl' => $this->getCancelUrl(),
                    'completeUrl' => $this->getReturnUrl(),
                    'callbackUrl' => $this->getNotifyUrl(),
                ],
                'payeeInfo' => [
                    'payeeId' => $this->getPayeeId(),
                    'payeeReference' => $this->getPayeeReference(),
                ],
                'metadata' => $this->getMetaData(),
            ],
        ];
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->productionEndpoint;
    }
}
