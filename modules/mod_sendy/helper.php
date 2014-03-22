<?php
/**
 * @package         Sendy Module
 * @copyright (c)   2014 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v1.0
 * @build-date      2014-03-05
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class modSendyHelper
{
    var $params;
    var $module;
    public function __construct($module)
    {
        $params = new JRegistry;
  		$params->loadString($module->params);
        $this->params = $params;
        $this->module = $module;
    }

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

/*    protected function getView()
    {
        switch ($this->params->get('subscribed_view'))
        {
            case ''
        }
    }*/

    public function render()
    {
        require(JModuleHelper::getLayoutPath('mod_sendy', 'subscribe'));
    }

    public function subscribeUser($email)
    {
        $http = JHttpFactory::getHttp();
        $data = array();
        $data['email'] = $email;
        $data['list'] = $this->params->get('list_id');
        $data['boolean'] = 'true';

        $return = $http->post($this->subscribeUrl, $data);
        $return = $return->body;
        return $return;
    }
}