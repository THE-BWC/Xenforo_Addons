<?php

namespace src\addons\Patrick\LogAllModActions\XF\ModeratorLog;

use Patrick\LogAllModActions\XF\ModeratorLog\XFCP_User;

class User extends XFCP_User
{
        public function isLoggableUser(\XF\Entity\User $actor)
        {
            if (\XF::options()->LogAllModActions_User)
            {
                return ($actor->user_id);
            }

		    return parent::isLoggableUser($actor);
        }
}
