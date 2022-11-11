<?php

namespace Empatix\OmnipaySwedbank\Messages;

use Empatix\OmnipaySwedbank\Gateway;
use Empatix\OmnipaySwedbank\Messages\Response;
use Empatix\OmnipaySwedbank\Messages\PurchaseRequest;

class CaptureRequest extends PurchaseRequest
{
    public function setPaymentId($value)
    {
        $this->setParameter('paymentId', $value);
    }

    public function getPaymentId()
    {
        return $this->getParameter('paymentId');
    }

    public function setPurchaseId($value)
    {
        $this->setParameter('purchaseId', $value);
    }

    public function getPurchaseId()
    {
        return $this->getParameter('purchaseId');
    }

    public function getData()
    {
        return [
            'transaction' => [
                'amount' => $this->getAmount(),
                'vatAmount' => $this->getVatAmount(),
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
            json_encode($data)
        );

        return $this->response = new Response(
            $this,
            $result->getBody()->getContents()
        );
    }
}
