<?php

namespace BWC\ApiExtension\Api\Controller;

use XF\Mvc\Entity\Entity;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\Exception;

use function is_array, is_string, strlen;

/**
 * @api-group Auth
 */
// Its own controller for its own route (auth/from-session). It doesn't extend XenForo's Auth controller:
// core already serves POST auth, and a route that names this class loads it directly, so a class
// extension's XFCP_ parent would never be defined.
class Auth extends \XF\Api\Controller\AbstractController
{
    /**
     * @throws Exception
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertSuperUserKey();
        $this->assertApiScope('auth');
    }

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
     *
     * @throws Exception
     */
    public function actionPostFromSession()
    {
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
                if (!$user)
                {
                    // The remember record outlived its user.
                    return $this->apiBoolResult(false);
                }

                return $this->apiSuccess([
                    'user' => $user->toApiResult(Entity::VERBOSITY_VERBOSE, ['full_profile' => true])
                ]);
            }
        }

        return $this->apiBoolResult(false);
    }

    public function apiBoolResult(bool $success, array $extra = []): \XF\Api\Mvc\Reply\ApiResult
    {
        return $this->apiResult(['success' => $success] + $extra);
    }

    protected function validateIpAgainstSession(array $sessionData, string $expectedIp): bool
    {
        // This is basically copied out of the session class...
        if (empty($sessionData['_ip']) || empty($expectedIp))
        {
            return true; // No IP to check against
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
                    // We have a user and the password date matches, so we can consider them logged in
                    return $user;
                }
            }
        }

        return $this->repository('XF:User')->getGuestUser();
    }
}
