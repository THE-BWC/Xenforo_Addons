<?php

namespace Patrick\LogAllModActions\XF\ModeratorLog;
use XF\Mvc\Entity\Entity;

class ProfilePost extends XFCP_ProfilePost
{
        public function isLoggableUser(\XF\Entity\User $actor)
        {
            if (\XF::options()->LogAllModActions_ProfilePost)
            {
                return ($actor->user_id);
            }

		    return parent::isLoggableUser($actor);
        }

        public function isLoggable(Entity $content, $action, \XF\Entity\User $actor)
        {
            if (\XF::options()->LogAllModActions_OwnActions)
            {
                switch ($action)
                {
                    case 'edit':
                        if ($actor->user_id == $content->user_id)
                        {
                            return true;
                        }
                }
            }
            return parent::isLoggable($content, $action, $actor);
        }
}
