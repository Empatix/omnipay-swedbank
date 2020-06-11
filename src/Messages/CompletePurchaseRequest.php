<?php

namespace Empatix\OmnipaySwedbank\Messages;

use Empatix\OmnipaySwedbank\Gateway;
use Empatix\OmnipaySwedbank\Messages\Response;

class CompletePurchaseRequest extends PurchaseRequest
{
    public function sendData($data)
    {
        $url = $this->getEndpoint() . $this->getTransactionReference();

        $httpResponse = $this->httpClient->request('GET', $url, [
            'Authorization' => 'Bearer ' . $this->getPassword(),
        ]);

        return $this->response = new Response(
            $this,
            json_decode($httpResponse->getBody()->getContents(), true)
        );
    }
}
