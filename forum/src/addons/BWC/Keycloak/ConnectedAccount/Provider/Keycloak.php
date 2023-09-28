<?php

namespace BWC\Keycloak\ConnectedAccount\Provider;

use XF\ConnectedAccount\Http\HttpResponseException;
use XF\ConnectedAccount\Provider\AbstractProvider;
use XF\Entity\ConnectedAccountProvider;

class Keycloak extends AbstractProvider
{
    public function getOAuthServiceName()
    {
        return 'BWC\Keycloak:Service\Keycloak';
    }

    public function getProviderDataClass()
    {
        return 'BWC\Keycloak:ProviderData\Keycloak';
    }

    public function getDefaultOptions()
    {
        return [
            'client_id' => '',
            'client_secret' => ''
        ];
    }

    public function getOAuthConfig(ConnectedAccountProvider $provider, $redirectUri = null)
    {
        return [
            'key' => $provider->options['client_id'],
            'secret' => $provider->options['client_secret'],
            'scopes' => ['openid','email'],
            'redirect' => $redirectUri ?: $this->getRedirectUri($provider),
        ];
    }

    public function parseProviderError(HttpResponseException $e, &$error = null)
    {
        $errorDetails = json_decode($e->getResponseContent(), true);
        if (isset($errorDetails['error_description']))
        {
            $e->setMessage($errorDetails['error_description']);
        }
        parent::parseProviderError($e, $error);
    }
}