<?php

namespace BWC\ApiExtension\Pub\Controller;

use XF\PrintableException;

class Login extends XFCP_Login
{
    /**
     * @throws PrintableException
     */
    public function actionApiToken()
    {
        $tokenValue = $this->filter('token', 'str');

        /** @var \BWC\ApiExtension\Entity\ApiLoginToken $token */
        $token = $this->em()->findOne('BWC\ApiExtension:ApiLoginToken', [
            'login_token' => $tokenValue
        ]);

        if (!$token || !$token->isValid($this->request->getIp()))
        {
            return $this->error(\XF::phrase('page_no_longer_available_back_try_again'));
        }

        $visitor = \XF::visitor();
        $force = $this->filter('force', 'bool');
        $remember = $this->filter('remember', 'bool');

        if ($visitor->user_id != $token->User->user_id)
        {
            if (!$visitor->user_id || $force)
            {
                /** @var \XF\ControllerPlugin\Login $loginPlugin */
                $loginPlugin = $this->plugin('XF:Login');

                if ($visitor->user_id && $force)
                {
                    $loginPlugin->logOutVisitor();
                }

                $loginPlugin->completeLogin($token->User, $remember);
            }
        }

        $token->delete();

        $returnUrl = $this->filter('return', 'str');
        if (!$returnUrl)
        {
            $returnUrl = $this->buildLink('index');
        }

        return $this->redirect($returnUrl);
    }
}