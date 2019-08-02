<?php
/**
 * @package     SendyModule
 * @subpackage  plg_ajax_sendy
 *
 * @author      SourceCoast <support@sourcecoast.com>
 * @copyright   (C) 2014 by Source Coast - All rights reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * Plugin class for sendy ajax request.
 *
 * @since  1.0.0
 */
class PlgAjaxSendy extends JPlugin
{
	protected $errorMessage;

	/**
	 * Function for sendy ajax
	 *
	 * @since   1.0
	 *
	 * @return mixed array or false
	 */
	public function onAjaxSendy()
	{
		// Session check
		if (JSession::checkToken('POST'))
		{
			$captchaStatus = $this->isCaptchaCorrect();

			$response          = new stdClass;
			$response->success = 'false';
			$response->html    = "";
			$return            = false;

			$post  = JFactory::getApplication()->input->post;
			$name  = $post->get('name', '', 'POST', 'email');
			$email = $post->get('email', '', 'POST', 'email');
			$id    = $post->get('mid', '', 'POST', 'INT');

			// Check for valid captcha
			if (!empty($name) && !empty($email) && !empty($id) && !empty($captchaStatus))
			{
				/* We could just pass the Joomla list ID directly as a hidden param in the module,
				but that leaves things open for nefarious people to try and subscribe to any list configured.
				With the below, we're loading the LID directly from the module.
				That means that only LIDs that are configured to be subscribed to using
				a module could be surreptitiously subscribed to.
				It's likely not a huge deal, but */
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select("*")
					->from('#__modules')
					->where($db->qn('id') . '=' . $db->q($id));
				$db->setQuery($query);
				$module = $db->loadObject();

				$params = new JRegistry;
				$params->loadString($module->params);

				$data          = array();
				$data['name']  = $name;
				$data['email'] = $email;

				jimport('sendy.sendy');
				$sendy  = Sendy::getInstance();
				$return = $sendy->subscribeUser($data, $params->get('list_id'));
			}

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

				// Load language file
				$lang = JFactory::getLanguage();
				$lang->load('mod_sendy', JPATH_SITE);

				jimport('joomla.application.module.helper');
				ob_start();
				require JModuleHelper::getLayoutPath('mod_sendy', $tmpl);
				$response->html = ob_get_clean();
			}

			$response->errorMessage = $this->errorMessage;

			echo json_encode($response);
		}

		exit;
	}

	/**
	 * Called to validate captcha
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function isCaptchaCorrect()
	{
		$input = JFactory::getApplication()->input;
		$post  = $input->post;

		try
		{
			JPluginHelper::importPlugin('captcha');
			$dispatcher = JDispatcher::getInstance();
			$input->set('recaptcha_response_field', $post->get('recaptcha_response_field', '', 'STRING'));
			$result     = $dispatcher->trigger('onCheckAnswer', 'asdasd');

			if ($result[0] == 1)
			{
				return true;
			}
		}
		catch (Exception $e)
		{
			$this->errorMessage = $e->getMessage();

			return false;
		}
	}
}
