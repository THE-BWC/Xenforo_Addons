<?php

namespace BWC\Keycloak\ConnectedAccount\ProviderData;

use XF\ConnectedAccount\ProviderData\AbstractProviderData;

class Keycloak extends AbstractProviderData
{
    public function getDefaultEndpoint()
    {
        return 'https://auth.the-bwc.local/realms/bwc/protocol/openid-connect/userinfo';
    }

    public function getProviderKey()
    {
        return $this->requestFromEndpoint('sub');
    }

    public function getEmail()
    {
        return $this->requestFromEndpoint('email');
    }
}