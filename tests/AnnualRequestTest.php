<?php

namespace Empatix\OmnipaySwedbank\Tests;

use Omnipay\Tests\TestCase;
use Empatix\OmnipaySwedbank\Messages\Response;
use Empatix\OmnipaySwedbank\Messages\AnnulRequest;

class AnnualRequestTest extends TestCase
{
    private $request;
    private $httpRequest;

    public function setUp(): void
    {
        $client = $this->getHttpClient();
        $this->httpRequest = $this->getHttpRequest();

        $this->request = new AnnulRequest($client, $this->httpRequest);
    }

    /** @test */
    public function it_gets_correct_data()
    {
        $this->request->setDescription('Test');
        $this->request->setPayeeReference('foo-ref');

        $this->assertEquals([
            'transcation' => [
                'description' => 'Test',
                'payeeReference' => 'foo-ref',
            ],
        ], $this->request->getData());
    }

    /** @test */
    public function annul_succes()
    {
        $httpResponse = $this->getMockHttpResponse('AnnulSuccess.txt');
        $response = new Response($this->getMockRequest(), json_decode($httpResponse->getBody()->getContents(), true), $httpResponse->getStatusCode());

       $this->assertTrue($response->isSuccessful());

       $this->assertTrue($response->getData()['cancellations']['id'] == "/psp/creditcard/payments/7e6cdfc3-1276-44e9-9992-7cf4419750e1/cancellations");
    }

    /** @test */
    public function annul_failure()
    {
        $httpResponse = $this->getMockHttpResponse('AnnulFailure.txt');
        $response = new Response($this->getMockRequest(), json_decode($httpResponse->getBody()->getContents(), true), $httpResponse->getStatusCode());

        $this->assertFalse($response->isSuccessful());
    }
}
