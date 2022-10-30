<?php

namespace Patrick\LogAllModActions\XF\ModeratorLog;
use XF\Mvc\Entity\Entity;

class Thread extends XFCP_Thread
{
        public function isLoggableUser(\XF\Entity\User $actor)
        {
            if (\XF::options()->LogAllModActions_Thread)
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
                    case 'title':
                    case 'prefix_id':
                    case 'custom_fields':
                        if ($actor->user_id == $content->user_id)
                        {
                            return true;
                        }
                }
            }
            return parent::isLoggable($content, $action, $actor);
        }
}
