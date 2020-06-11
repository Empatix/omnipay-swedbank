<?php

namespace Empatix\OmnipaySwedbank\Messages;

use Empatix\OmnipaySwedbank\Gateway;
use Empatix\OmnipaySwedbank\Messages\Response;
use Empatix\OmnipaySwedbank\Messages\PurchaseRequest;

class AnnulRequest extends PurchaseRequest
{
    public function setPassword($value)
    {
        $this->setParameter('password', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setPayeeReference($value)
    {
        $this->setParameter('payeeReference', $value);
    }

    public function getPayeeReference()
    {
        return $this->getParameter('payeeReference');
    }

    public function getData()
    {
        return [
            'transcation' => [
                'description' => $this->getDescription(),
                'payeeReference' => $this->getPayeeReference(),
            ],
        ];
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
            json_encode($data),
        );

        return $this->response = new Response(
            $this,
            $result->getBody()->getContents(),
        );
     }
}
