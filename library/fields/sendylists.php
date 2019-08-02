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

jimport('joomla.form.helper');
jimport('sendy.sendy');

JFormHelper::loadFieldClass('list');

/**
 * Sendy lists field class
 *
 * @since  1.0.0
 */
class JFormFieldSendyLists extends JFormFieldList
{
	public $type = 'SendyLists';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.0.0
	 */
	protected function getOptions()
	{
		$options = array();

		$sendy = Sendy::getInstance();
		$lists = $sendy->getLists();

		$options[] = JHtml::_('select.option', "0", "-- Select a Sendy List --");

		foreach ($lists as $key => $list)
		{
			$options[] = JHtml::_('select.option', $key, $list->name);
		}

		return $options;
	}

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0.0
	 */
	protected function getInput()
	{
		if (count($this->getOptions()) <= 1)
		{
			return "<label>No lists setup! Please enable and configure the Sendy System Plugin first.</label>";
		}
		else
		{
			return parent::getInput();
		}
	}
}
