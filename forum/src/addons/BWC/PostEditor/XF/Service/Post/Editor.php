<?php

namespace BWC\PostEditor\XF\Service\Post;

class Editor extends XFCP_Editor
{
    protected function setupEditHistory($oldMessage)
    {
        parent::setupEditHistory($oldMessage);

        $post = $this->post;
        $options = $this->app->options();

        if ($options->editLogDisplay['enabled'] && $this->logEdit)
        {
            $delay = is_null($this->logDelay) ? $options->editLogDisplay['delay'] * 60 : $this->logDelay;
            if ($post->post_date + $delay <= \XF::$time)
            {
                if (isset($post->last_edit_user_id)) {
                    $post->bwc_posteditor_last_edit_username = \XF::visitor()->username;
                }
            }
        }
    }
}