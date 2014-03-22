<?php
/**
 * @package        Sendy
 * @copyright (C) 2014 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.event.plugin');

class plgUserSendy extends JPlugin
{
    function plgUserSendy(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->isActivating = false;
    }

    public function onUserBeforeSave($oldUser, $isnew, $user)
    {
        $this->isActivating = false; // always revert back
        if (($oldUser['block'] == 1 && $user['block'] == 0) ||
                ($isnew && $user['block'] == 0)
        )
        {
            $this->isActivating = true;
        }
    }

    public function onUserAfterSave($user, $isnew, $success, $msg)
    {
        // Check if the user is new and not blocked Or
        // If the user was blocked but isn't anymore (they just activated)
        if ($this->isActivating)
        {
            $list = $this->params->get('list_id');
            if ($list)
            {
                $email = $user['email'];
                Sendy::getInstance()->subscribeUser($email, $list);
            }
        }
    }
}
