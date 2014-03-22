<?php
/**
 * @package    Sendy Module - New email subscription.
 *
 * @copyright  Copyright (C) 2014 SourceCoast, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// We are a valid entry point.
const _JEXEC = 1;

define('JPATH_BASE', dirname(__DIR__) . '/../../');
require_once JPATH_BASE . '/includes/defines.php';

// Get the framework.
if (is_file(JPATH_LIBRARIES . '/import.legacy.php'))
    require_once JPATH_LIBRARIES . '/import.legacy.php';
else
    require_once JPATH_LIBRARIES . '/import.php';

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

class modSendySubscribeWeb extends JApplicationWeb
{
    public function doExecute()
    {
        // Session check
        if (JSession::checkToken('POST'))
        {
            $response = new stdClass();
            $response->success = 'false';
            $response->html = "";

            $email = JRequest::getVar('email', '', 'POST', 'email');
            $id = JRequest::getVar('mid', '', 'POST', 'INT');

            // We could just pass the Joomla list ID directly as a hidden param in the module,
            //   but that leaves things open for nefarious people to try and subscribe to any list configured.
            // With the below, we're loading the LID directly from the module.
            // That means that only LIDs that are configured to be subscribed to using
            //   a module could be surreptitiously subscribed to.
            // It's likely not a huge deal, but
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select("*")
                ->from('#__modules')
                ->where($db->qn('id') . '=' . $db->q($id));
            $db->setQuery($query);
            $module = $db->loadObject();

            $params = new JRegistry;
      		$params->loadString($module->params);

            jimport('sendy.sendy');
            $sendy = Sendy::getInstance();
            $return = $sendy->subscribeUser($email, $params->get('list_id'));
            if ($return)
            {
                $response->success = true;
                if ($return == '1')
                {
                    $tmpl = "thanks";
                }
                else
                {
                    $tmpl = "error";
                }
                jimport('joomla.application.module.helper');
                ob_start();
                require JModuleHelper::getLayoutPath('mod_sendy', $tmpl);
                $response->html = ob_get_clean();
            }
            echo json_encode($response);
        }

        exit;
    }
}

// Instantiate the application object, passing the class name to JCli::getInstance
// and use chaining to execute the application.
$app = JApplicationWeb::getInstance('modSendySubscribeWeb');
// Loading the 'site' application to make sure our session (and other) data is from the 'site'.. sounds obvious, but documenting it, because it won't be the next time I look at this.
JFactory::getApplication('site');
$app->execute();