<?php

namespace src\addons\Patrick\LogAllModActions\XF\ModeratorLog;
use Patrick\LogAllModActions\XF\ModeratorLog\XFCP_Post;
use XF\Mvc\Entity\Entity;

class Post extends XFCP_Post
{
        public function isLoggableUser(\XF\Entity\User $actor)
        {
            if (\XF::options()->LogAllModActions_Post)
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
