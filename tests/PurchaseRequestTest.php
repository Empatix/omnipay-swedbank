<?php

namespace Empatix\OmnipaySwedbank\Tests;

use Omnipay\Tests\TestCase;
use Empatix\OmnipaySwedbank\Messages\Response;
use Empatix\OmnipaySwedbank\Messages\PurchaseRequest;

class PurchaseRequestTest extends TestCase
{
    private $request;
    private $httpRequest;

    public function setUp(): void
    {
        $client = $this->getHttpClient();
        $this->httpRequest = $this->getHttpRequest();

        $this->request = new PurchaseRequest($client, $this->httpRequest);
    }

    /** @test */
    public function it_gets_correct_data()
    {
        $this->request->setAmount('750');
        $this->request->setCurrency('NOK');
        $this->request->setVatAmount('25');
        $this->request->setLanguage('no_NO');
        $this->request->setMetaData([
            'foo' => 'bar',
        ]);
        $this->request->setUserAgent('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36');
        $this->request->setDescription('This is what you are paying for');
        $this->request->setCancelUrl('https://merchant-website/payment/cancel');
        $this->request->setCompleteUrl('https://merchant-website/payment/complete');
        $this->request->setNotifyUrl('https://merchant-website/payment/callback');
        $this->request->setPayeeId('foo-payee-id');
        $this->request->setPayeeReference("1bd0558b-3a45-46af-b07a-c8cfdf298cd8");

        $this->assertEquals([
            "payment" => [
                "operation" => "Purchase",
                "intent" => "AutoCapture",
                "currency" => "NOK",
                "prices" => [
                    [
                    "vatAmount" => 25,
                    "type" => "CreditCard",
                    "amount" => 75000,
                    ]
                ],
                "language" => "no_NO",
                "userAgent" => "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36",
                "description" => "This is what you are paying for",
                "urls" => [
                    "cancelUrl" => "https://merchant-website/payment/cancel",
                    "completeUrl" => "https://merchant-website/payment/cancel",
                    'callbackUrl' => 'https://merchant-website/payment/callback',
                ],
                "payeeInfo" => [
                    "payeeId" => "foo-payee-id",
                    "payeeReference" => "1bd0558b-3a45-46af-b07a-c8cfdf298cd8",
                ],
                'metadata' => [
                    'foo' => 'bar',
                ],
            ]
        ], $this->request->getData());
    }

    /** @test */
    public function purchase_success()
    {
       $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
       $response = new Response($this->getMockRequest(), json_decode($httpResponse->getBody()->getContents(), true), $httpResponse->getStatusCode());

       $this->assertTrue($response->isSuccessful());
       $this->assertTrue($response->getTransactionReference() == "/psp/creditcard/payments/7e6cdfc3-1276-44e9-9992-7cf4419750e1");
    }

    /** @test */
    public function purchase_failure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new Response($this->getMockRequest(), json_decode($httpResponse->getBody()->getContents(), true), $httpResponse->getStatusCode());

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue(is_null($response->getTransactionReference()));
    }
}
