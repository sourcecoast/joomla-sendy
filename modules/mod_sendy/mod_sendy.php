<?php
/**
 * @package        Sendy Module
 * @copyright (C) 2014 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

include_once('helper.php');
$helper = new modSendyHelper($module);
$helper->render();

?>
