<?php

namespace Empatix\OmnipaySwedbank\Messages;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class Response extends AbstractResponse implements RedirectResponseInterface
{
    public function getTransactionReference()
    {
        if (isset($this->data['payment']['id'])) {
            return $this->data['payment']['id'];
        }
    }

    public function isSuccessful()
    {
        if (isset($this->data['reversal']['transaction']['state'])) {
            return 'Completed' === $this->data['reversal']['transaction']['state'];
        }

        if (isset($this->data['cancellations'])) {
            $cancellations = $this->data['cancellations']['cancellationList'];

            return 'Completed' === $cancellations[0]['transaction']['state'];
        }

        if (isset($this->data['capture']['transaction']['state'])) {
            return 'Completed' === $this->data['capture']['transaction']['state'];
        }

        if (isset($this->data['payment']['state'])) {
            return 'Ready' === $this->data['payment']['state'];
        }

        return false;
    }

    public function isCancelled()
    {
        if (isset($this->data['payment']['state'])) {
            return 'Aborted' === $this->data['payment']['state'];
        }

        return false;
    }

    public function isPending()
    {
        if (isset($this->data['payment']['state'])) {
            return 'Pending' === $this->data['payment']['state'];
        }

        return false;
    }

    public function isRedirect()
    {
        return $this->getRedirectUrl() ? true : false;
    }

    public function getRedirectUrl()
    {
        return $this->getRedirectAuthorizationUrl();
    }

    public function getRedirectAuthorizationUrl()
    {
        if (isset($this->data['operations'])) {
            foreach (($this->data['operations']) as $operation) {
                if ($operation['rel'] == 'redirect-authorization') {
                    return $operation['href'];
                }
            }
        }
    }

    public function getMessage()
    {
        if (isset($this->data['status']) && isset($this->data['type'])) {
            return $this->data['title'];
        }

        return null;
    }

    public function getCode()
    {
        if (isset($this->data['status']) && isset($this->data['type'])) {
            return $this->data['status'];
        }

        return null;
    }
}
