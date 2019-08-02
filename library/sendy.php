<?php
/**
 * @package     Sendy
 * @subpackage  lib_sendy
 *
 * @author      SourceCoast <support@sourcecoast.com>
 * @copyright   (C) 2014 by Source Coast - All rights reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Sendy class
 *
 * @since  1.0.0
 */
class Sendy
{
	protected $apiKey;

	protected $params;

	// Really just a helper to prevent
	protected static $instance;

	/**
	 * Constructor method
	 */
	public function __construct()
	{
		jimport('joomla.plugin.helper');
		$this->params = new JRegistry;
		$plugin       = JPluginHelper::getPlugin('system', 'sendy');

		if ($plugin)
		{
			$this->params->loadString($plugin->params);
		}
	}

	/**
	 * Get instance
	 *
	 * @return  object
	 */
	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new Sendy;
		}

		return self::$instance;
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
				return $this->params->get('sendy_url');
			case 'subscribeUrl':
				return $this->sendyUrl . '/subscribe';
			case 'unsubscribeUrl':
				return $this->sendyUrl . '/unsubscribe';
		}
	}

	/**
	 * Subscribe user to list
	 *
	 * @param   array  $userData  User data from form
	 * @param   int    $listId    List ID
	 *
	 * @return  string
	 */
	public function subscribeUser($userData, $listId)
	{
		$list = null;
		$lists = $this->getLists();

		if (isset($lists[$listId]))
		{
			$list = $lists[$listId];
		}

		if ($list)
		{
			$http = JHttpFactory::getHttp();
			$data = array();
			$data['name'] = $userData['name'];
			$data['email'] = $userData['email'];
			$data['list'] = $list->id;
			$data['boolean'] = 'true';

			$return = $http->post($this->subscribeUrl, $data);
			$return = $return->body;
		}
		else
		{
			$return = "That list is not defined";
		}

		return $return;
	}

	/**
	 * Subscribe user to list
	 *
	 * @param   array  $email   User email from form
	 * @param   int    $listId  List ID
	 *
	 * @return  string
	 */
	public function unsubscribeUser($email, $listId)
	{
		$list = null;

		$lists = $this->getLists();

		if (isset($lists[$listId]))
		{
			$list = $lists[$listId];
		}

		if ($list)
		{
			$http = JHttpFactory::getHttp();
			$data = array();
			$data['email'] = $email;
			$data['list'] = $list->id;
			$data['boolean'] = 'true';

			$return = $http->post($this->unsubscribeUrl, $data);
			$return = $return->body;
		}
		else
		{
			$return = "That list is not defined";
		}

		return $return;
	}

	/**
	 * Get Lists
	 *
	 * @return  object
	 */
	public function getLists()
	{
		$lists = array();

		$options[] = JHtml::_('select.option', "--", "-- Select a Sendy List --");

		for ($i = 1; $i < 20; $i++)
		{
			if ($this->params->get('list' . $i . '_id') != null)
			{
				$list       = new stdClass;
				$list->name = $this->params->get('list' . $i . '_name');
				$list->id   = $this->params->get('list' . $i . '_id');
				$lists[$i]  = $list;
			}
		}

		return $lists;
	}
}
