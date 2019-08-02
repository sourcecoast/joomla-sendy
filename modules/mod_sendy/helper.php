<?php
/**
 * @package     SendyModule
 * @subpackage  mod_sendy
 *
 * @author      SourceCoast <support@sourcecoast.com>
 * @copyright   (C) 2014 by Source Coast - All rights reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Module helper class
 *
 * @since  1.0.0
 */
class ModSendyHelper
{
	protected $params;

	protected $module;

	/**
	 * Constructor method
	 *
	 * @param   object  $module  Joomla module
	 */
	public function __construct($module)
	{
		$params = new JRegistry;
		$params->loadString($module->params);
		$this->params = $params;
		$this->module = $module;
	}

	/**
	 * Get Url
	 *
	 * @param   string  $name  URL type
	 *
	 * @return  string
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'sendyUrl':
				return trim($this->params->get('sendy_url'), '/');
			case 'subscribeUrl':
				return $this->sendyUrl . '/subscribe';
			case 'unsubscribeUrl':
				return $this->sendyUrl . '/unsubscribe';
		}
	}

	/*protected function getView()
	{
		switch ($this->params->get('subscribed_view'))
		{
			case ''
		}
	}*/

	/**
	 * Renders module layout
	 *
	 * @return  void
	 */
	public function render()
	{
		require JModuleHelper::getLayoutPath('mod_sendy', 'subscribe');
	}

	/**
	 * Subscribe user to list
	 *
	 * @param   array  $userData  User data from form
	 *
	 * @return  object  Jhttp object
	 */
	public function subscribeUser($userData)
	{
		$http = JHttpFactory::getHttp();
		$data = array();

		$data['name']    = $userData['name'];
		$data['email']   = $userData['email'];
		$data['list']    = $this->params->get('list_id');
		$data['boolean'] = 'true';

		$return = $http->post($this->subscribeUrl, $data);
		$return = $return->body;

		return $return;
	}
}
