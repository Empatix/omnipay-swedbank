<?php

namespace Empatix\OmnipaySwedbank\Tests;

use Omnipay\Tests\TestCase;
use Empatix\OmnipaySwedbank\Messages\Response;
use Empatix\OmnipaySwedbank\Messages\CaptureRequest;

class CaptureRequestTest extends TestCase
{
    private $request;
    private $httpRequest;

    public function setUp(): void
    {
        $client = $this->getHttpClient();
        $this->httpRequest = $this->getHttpRequest();

        $this->request = new CaptureRequest($client, $this->httpRequest);
    }

    /** @test */
    public function it_gets_correct_data()
    {
        $this->request->setAmount('1000');
        $this->request->setVatAmount('25');
        $this->request->setPayeeReference('foo-ref');
        $this->request->setDescription('This is what you are paying for');

        $this->assertEquals([
            'transaction' => [
                'amount' => 1000,
                'vatAmount' => '25',
                'payeeReference' => 'foo-ref',
                'description' => 'This is what you are paying for'
            ],
        ], $this->request->getData());
    }

    /** @test */
    public function capture_success()
    {
        $httpResponse = $this->getMockHttpResponse('CaptureSuccess.txt');
        $response = new Response($this->getMockRequest(), json_decode($httpResponse->getBody()->getContents(), true), $httpResponse->getStatusCode());

       $this->assertTrue($response->isSuccessful());
       $this->assertTrue(! is_null($response->getData()['capture']['id']));
    }

    /** @test */
    public function capture_fail()
    {
        $httpResponse = $this->getMockHttpResponse('CaptureFailure.txt');
        $response = new Response($this->getMockRequest(), json_decode($httpResponse->getBody()->getContents(), true), $httpResponse->getStatusCode());

        $this->assertFalse($response->isSuccessful());
    }
}
