<?php

namespace BWC\ApiExtension\Api\Controller;

use XF\Mvc\Reply\Exception;

/**
 * @api-group ConnectedAccounts
 */
class ConnectedAccounts extends \XF\Api\Controller\AbstractController
{
        /**
     * @api-desc Gets the connected accounts for the specified user.
     *
     * @api-in <req> int $user_id The ID of the user to get connected accounts for.
     *
     * @api-out ConnectedAccount[] $connected_accounts
     *
     * @throws Exception
     */
    public function actionGet(): \XF\Api\Mvc\Reply\ApiResult
    {
        $this->assertApiScope('user:connected_accounts');
        $this->assertRequiredApiInput('provider_key');

        $providerKey = $this->filter('provider_key', 'uint');

        $connectedAccounts = $this->finder('XF:UserConnectedAccount')
            ->where('provider_key', $providerKey)
            ->fetch();

        foreach ($connectedAccounts as $connectedAccount) {
            $result[] = array(
                'user_id' => $connectedAccount->user_id,
                'provider' => $connectedAccount->provider,
                'provider_key' => $connectedAccount->provider_key
            );
        }

        return $this->apiResult($result);
    }
}