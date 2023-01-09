<?php

namespace Empatix\OmnipaySwedbank;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Swedbank Payments';
    }

    public function getDefaultParameters()
    {
        return [
            'merchantId' => '',
            'password' => '',
            'testMode' => false,
        ];
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('Empatix\OmnipaySwedbank\Messages\PurchaseRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('Empatix\OmnipaySwedbank\Messages\CaptureRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('Empatix\OmnipaySwedbank\Messages\CompletePurchaseRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('Empatix\OmnipaySwedbank\Messages\AnnulRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('Empatix\OmnipaySwedbank\Messages\ReversalRequest', $parameters);
    }
}
