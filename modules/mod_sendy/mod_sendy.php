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

include_once 'helper.php';
$helper = new modSendyHelper($module);
$helper->render();
