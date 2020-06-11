<?php

namespace Empatix\OmnipaySwedbank\Messages;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class Response extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        if (array_key_exists('reversal', $this->getData())) {
            $state = $this->getData()['reversal']['transaction']['state'];
            return $state == 'Completed';
        }

        if (array_key_exists('cancellations', $this->getData())) {
            $cancellations = $this->getData()['cancellations']['cancellationList'];
            $state = $cancellations[0]['transaction']['state'];
            return $state == 'Completed';
        }

        if (array_key_exists('capture', $this->getData())) {
            $state = $this->getData()['capture']['transaction']['state'];
            return $state == 'Completed';
        }

        if (array_key_exists('payment', $this->getData())) {
            if (array_key_exists('state', $this->getData()['payment'])) {
                $state = $this->getData()['payment']['state'];
                return $state == 'Ready';
            }
        }

        return false;
    }

    public function getTransactionReference()
    {
        if (array_key_exists('payment', $this->getData())) {
            return $this->getData()['payment']['id'];
        }
    }

    public function isRedirect()
    {
        if ($this->getRedirectAuthorizationUrl()) {
            return true;
        }
    }

    public function getRedirectUrl()
    {
        if ($url = $this->getRedirectAuthorizationUrl()) {
            return $url;
        }
    }

    public function getRedirectAuthorizationUrl()
    {
        if (array_key_exists('operations', $this->getData())) {
            foreach (($this->getData()['operations']) as $operation) {
                if ($operation['rel'] == 'redirect-authorization') {
                    return $operation['href'];
                }
            }
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function getRedirectMethod()
    {
        return "GET";
    }
}
