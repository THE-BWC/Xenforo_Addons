<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */

namespace Patrick\ApiExtension\XF\API\Controller;

use XF\Api\Controller\AbstractController;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\AbstractReply;
use XF\Mvc\Reply\Exception;
use XF\Mvc\RouteMatch;

use XF\Mvc\Entity\Entity;

class Auth extends XFCP_Auth
{

    /**
     * @throws Exception
     */
    public function actionPostFromSession(): \XF\Api\Mvc\Reply\ApiResult
    {
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

        return (new ApiResults)->apiBoolResult(false);
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

    protected function getUserFromSessionData(array $sessionData): Entity
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

}