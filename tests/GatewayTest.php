<?php

namespace Empatix\OmnipaySwedbank;

use Omnipay\Tests\GatewayTestCase;
use Empatix\OmnipaySwedbank\Gateway;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setMerchantId('foo');
        $this->gateway->setPassword('bar');
    }
}
