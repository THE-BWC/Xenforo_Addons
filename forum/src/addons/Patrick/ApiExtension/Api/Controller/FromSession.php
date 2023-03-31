<?php

namespace Patrick\ApiExtension\Api\Controller;

// Before you ask. For some reason, the API doesn't seem to either use or like the XFCP_ prefix/extension system. Even though it's valid PHP, the IDE doesn't complain, and it works in the other add-ons.
// So I'm just going to require the extension_hint here. It's not like it's going to change. Plus this keeps us 'compliant' with the XF2 extension system.
require_once($_SERVER['DOCUMENT_ROOT']."/forum/src/addons/Patrick/ApiExtension/_output/extension_hint.php");

use XF\Api\Mvc\Reply\ApiResult;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Reply\Exception;

use function is_array, is_string, strlen;

class FromSession extends XFCP_FromSession
{
    /**
     * @api-desc Looks up the active XenForo user based on session ID or remember cookie value.
     *      This can be used to help with seamless SSO with XF, assuming the session or remember cookies are
     *      available to your page. At least one of session_id and remember_cookie must be provided.
     *      Only available to super user keys.
     *
     * @api-in str $session_id If provided, checks for an active session with that ID.
     * @api-in str $remember_cookie If provided, checks to see if this is an active "remember me" cookie value.
     *
     * @api-out bool $success If false, no session or remember cookie could be found
     * @api-out User $user If successful, the user record of the matching user. May be a guest.
     * @throws Exception
     */
    public function actionPostFromSession(): ApiResult
    {
        // Check that the user is a super user and has the correct scope
        $this->assertSuperUserKey();
        $this->assertApiScope('auth');

        $sessionId = $this->filter('session_id', 'str');
        $rememberCookie = $this->filter('remember_cookie', 'str');

        if (!$sessionId && !$rememberCookie)
        {
            $this->assertRequiredApiInput(['session_id', 'remember_cookie']);
        }

        if ($sessionId)
        {
            /** @var \XF\Session\StorageInterface $publicSessions */
            $publicSessions = $this->app->get('session.public.storage');
            $sessionData = $publicSessions->getSession($sessionId);

            if (is_array($sessionData))
            {
                $sessionIpLimit = $this->filter('session_ip_limit', '?str');
                if (is_string($sessionIpLimit))
                {
                    $ipValidated = $this->validateIpAgainstSession($sessionData, $sessionIpLimit);
                }
                else
                {
                    $ipValidated = true;
                }

                if ($ipValidated)
                {
                    $user = $this->getUserFromSessionData($sessionData);

                    return $this->apiSuccess([
                        'user' => $user->toApiResult(Entity::VERBOSITY_VERBOSE, ['full_profile' => true])
                    ]);
                }
            }
        }

        if ($rememberCookie)
        {
            /** @var \XF\Repository\UserRemember $rememberRepo */
            $rememberRepo = $this->repository('XF:UserRemember');

            if ($rememberRepo->validateByCookieValue($rememberCookie, $remember))
            {
                $user = $this->em()->find('XF:User', $remember->user_id, 'api');

                return $this->apiSuccess([
                    'user' => $user->toApiResult(Entity::VERBOSITY_VERBOSE, ['full_profile' => true])
                ]);
            }
        }

        return $this->apiBoolResult(false);
    }

    protected function validateIpAgainstSession(array $sessionData, string $expectedIp): bool
    {
        // this is basically copied out of the session class...

        if (empty($sessionData['_ip']) || empty($expectedIp))
        {
            return true; // no IP to check against
        }

        $expectedIp = \XF\Util\Ip::convertIpStringToBinary($expectedIp);

        $cidr = strlen($expectedIp) == 4 ? 24 : 64;

        if (empty($sessionData['userId']) || $cidr <= 0)
        {
            return true; // IP check disabled
        }

        return \XF\Util\Ip::ipMatchesCidrRange($expectedIp, $sessionData['_ip'], $cidr);
    }

    protected function getUserFromSessionData(array $sessionData): \XF\Entity\User
    {
        if (!empty($sessionData['userId']))
        {
            $user = $this->em()->find('XF:User', $sessionData['userId'], 'api');
            if ($user)
            {
                $userPasswordDate = $user->Profile ? $user->Profile->password_date : 0;
                if (!isset($sessionData['passwordDate']) || $sessionData['passwordDate'] == $userPasswordDate)
                {
                    // we have a user and the password date matches, so we can consider them logged in
                    return $user;
                }
            }
        }

        return $this->repository('XF:User')->getGuestUser();
    }

    public function apiBoolResult(bool $success, array $extra = []): ApiResult
    {
        return $this->apiResult(['success' => $success] + $extra);
    }

}