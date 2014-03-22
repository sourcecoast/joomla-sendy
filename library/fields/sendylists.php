<?php

/**
 * @package        JFBConnect
 * @copyright (C) 2009-2014 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.helper');
jimport('sendy.sendy');
JFormHelper::loadFieldClass('list');

class JFormFieldSendyLists extends JFormFieldList
{
    public $type = 'SendyLists';

    protected function getOptions()
    {
        $options = array();

        $sendy = Sendy::getInstance();
        $lists = $sendy->getLists();

        $options[] = JHtml::_('select.option', "0", "-- Select a Sendy List --");
        foreach ($lists as $key => $list)
                $options[] = JHtml::_('select.option', $key, $list->name);

        return $options;
    }

    protected function getInput()
    {
        if (count($this->getOptions()) <= 1)
            return "<label>No lists setup! Please enable and configure the Sendy System Plugin first.</label>";
        else
            return parent::getInput();
    }
}
