<?php

namespace Empatix\OmnipaySwedbank\Tests;

use Omnipay\Tests\TestCase;
use Empatix\OmnipaySwedbank\Messages\Response;
use Empatix\OmnipaySwedbank\Messages\ReversalRequest;

class ReversalRequestTest extends TestCase
{
    private $request;
    private $httpRequest;

    public function setUp(): void
    {
        $client = $this->getHttpClient();
        $this->httpRequest = $this->getHttpRequest();

        $this->request = new ReversalRequest($client, $this->httpRequest);
    }

    /** @test */
    public function it_gets_correct_data()
    {
        $this->request->setAmount('350');
        $this->request->setVatAmount('75');
        $this->request->setDescription('test reversal');
        $this->request->setPayeeReference('my-foo-payee-ref');

        $this->assertEquals($this->request->getData(), [
            'transaction' => [
                'amount' => 350,
                'vatAmount' => 75,
                'description' => 'test reversal',
                'payeeReference' => 'my-foo-payee-ref',
            ]
        ]);
    }

    /** @test */
    public function reversal_success()
    {
       $httpResponse = $this->getMockHttpResponse('ReversalSuccess.txt');
       $response = new Response($this->getMockRequest(), json_decode($httpResponse->getBody()->getContents(), true), $httpResponse->getStatusCode());

       $this->assertTrue($response->isSuccessful());
       $this->assertTrue($response->getData()['reversal']['id'] == "/psp/creditcard/payments/7e6cdfc3-1276-44e9-9992-7cf4419750e1/reversal/ec2a9b09-601a-42ae-8e33-a5737e1cf177");
    }
}
