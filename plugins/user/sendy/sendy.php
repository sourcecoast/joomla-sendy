<?php
/**
 * @package     Sendy
 * @subpackage  plg_user_sendy
 *
 * @author      SourceCoast <support@sourcecoast.com>
 * @copyright   (C) 2014 by Source Coast - All rights reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.event.plugin');

/**
 * Sendy user plugin class
 *
 * @since  1.0.0
 */
class PlgUserSendy extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 *
	 * @since   1.5
	 *
	 * @return void
	 */
	public function plgUserSendy(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->isActivating = false;
	}

	/**
	 * Method is called before user data is stored in the database
	 *
	 * @param   array    $oldUser  Holds the old user data.
	 * @param   boolean  $isnew    True if a new user is stored.
	 * @param   array    $user     Holds the new user data.
	 *
	 * @return  boolean
	 *
	 * @since   3.1
	 * @throws  InvalidArgumentException on invalid date.
	 */
	public function onUserBeforeSave($oldUser, $isnew, $user)
	{
		// Always revert back
		$this->isActivating = false;

		if (($oldUser['block'] == 1 && $user['block'] == 0)
			|| ($isnew && $user['block'] == 0))
		{
			$this->isActivating = true;
		}
	}

	/**
	 * Saves user profile data
	 *
	 * @param   array    $user     entered user data
	 * @param   boolean  $isnew    true if this is a new user
	 * @param   boolean  $success  true if saving the user worked
	 * @param   string   $msg      error message
	 *
	 * @return  boolean
	 */
	public function onUserAfterSave($user, $isnew, $success, $msg)
	{
		// Check if the user is new and not blocked Or
		// If the user was blocked but isn't anymore (they just activated)
		if ($this->isActivating)
		{
			$list = $this->params->get('list_id');

			if ($list)
			{
				$data = array();
				$data['name'] = $user['name'];
				$data['email'] = $user['email'];
				Sendy::getInstance()->subscribeUser($data, $list);
			}
		}
	}
}
