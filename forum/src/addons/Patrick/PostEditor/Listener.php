<?php

namespace src\addons\Patrick\PostEditor;

use XF\Mvc\Entity\Entity;

class Listener
{
    public static function postEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
    {
        $structure->columns['patrick_posteditor_last_edit_username'] = ['type' => Entity::STR, 'default' => '', 'maxlength' => 50];
    }
}