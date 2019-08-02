<?php
/**
 * @package     Sendy
 * @subpackage  plg_system_sendy
 *
 * @author      SourceCoast <support@sourcecoast.com>
 * @copyright   (C) 2014 by Source Coast - All rights reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.event.plugin');
jimport('sendy.sendy');

/**
 * Sendy system plugin class
 *
 * @since  1.0.0
 */
class PlgSystemSendy extends JPlugin
{
	/*
	 * Yup, there's nothing here. This plugin must be enabled though for:
	 * Importing the jimport statement above
	 * Allowing the plugin params to be fetched from the library, but configured from the admin area.
	 */
}
