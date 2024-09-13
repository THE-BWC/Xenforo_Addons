<?php

namespace BWC\Keycloak\ConnectedAccount\ProviderData;

use XF\ConnectedAccount\ProviderData\AbstractProviderData;

class Keycloak extends AbstractProviderData
{
    public function getDefaultEndpoint()
    {
        return 'https://auth.sw.patrickpedersen.tech/realms/bwc/protocol/openid-connect/userinfo';
    }

    public function getProviderKey()
    {
        return $this->requestFromEndpoint('sub');
    }

    public function getEmail()
    {
        $emailData = $this->requestFromEndpoint('email');

        if ($emailData)
        {
            return "$emailData";
        }

        return null;
    }
}