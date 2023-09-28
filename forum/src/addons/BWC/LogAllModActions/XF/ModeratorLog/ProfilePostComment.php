<?php

namespace BWC\LogAllModActions\XF\ModeratorLog;

use XF\Mvc\Entity\Entity;

class ProfilePostComment extends XFCP_ProfilePostComment
{
    public function isLoggableUser(\XF\Entity\User $actor)
    {
        if (\XF::options()->BWC_LogAllModActions_ProfilePostComment)
        {
            return ($actor->user_id);
        }
        return parent::isLoggableUser($actor);
    }

    public function isLoggable(Entity $content, $action, \XF\Entity\User $actor): bool
    {
        if (\XF::options()->BWC_LogAllModActions_OwnActions)
        {
            if ($action == 'edit') {
                if ($actor->user_id == $content->user_id) {
                    return true;
                }
            }
        }
        return parent::isLoggable($content, $action, $actor);
    }
}