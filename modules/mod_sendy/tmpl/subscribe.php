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
?>

<div class="sendy subscribe" id="sendy-<?php echo $this->module->id; ?>">
	<div class="form-group hide">
		<p id="sendy-subscribe-<?php echo $this->module->id; ?>-error-message" class="text text-danger"></p>
	</div>

	<div class="intro">
		<?php echo $this->params->get('introtext'); ?>
	</div>

	<form id="sendy-subscribe-<?php echo $this->module->id; ?>" class="modSendy">
		<div class="form-group">
			<label for="name" class="hide"><?php echo JText::_("MOD_SENDY_SUBSCRIBER_NAME");?> *</label>
			<input id="name" name="name" required="true" type="text" class="form-control "
				placeholder="<?php echo JText::_("MOD_SENDY_SUBSCRIBER_NAME");?> *"/>
		</div>

		<div class="form-group">
			<label for="email" class="hide"><?php echo JText::_("MOD_SENDY_SUBSCRIBER_EMAIL");?> *</label>
			<input id="email" name="email" required="true" type="text" class="form-control "
				placeholder="<?php echo JText::_("MOD_SENDY_SUBSCRIBER_EMAIL");?> *"/>
		</div>

		<div class="checkbox">
			<label>
				<input id="gdpr" name="gdpr" type="checkbox" required="true"/>
					* <strong><?php echo JText::_("MOD_SENDY_SUBSCRIBER_MARKETING_PERMISSION_LBL");?></strong>
					<?php echo JText::_("MOD_SENDY_SUBSCRIBER_MARKETING_PERMISSION_DESC");?>
			</label>
		</div>

		<div class="small">
			<p>
				<strong><?php echo JText::_("MOD_SENDY_SUBSCRIBER_WHAT_TO_EXPECT_LBL");?></strong> 
				<?php echo JText::_("MOD_SENDY_SUBSCRIBER_WHAT_TO_EXPECT_DESC");?>
			</p>
		</div>

		<div class="form-group">
			<?php echo JCaptcha::getInstance(JFactory::getConfig()->get('captcha'))->display('recaptcha', 'recaptcha', 'g-recaptcha'); ?>
		</div>

		<div>
			<button type="submit" name="Sign up" class="btn btn-primary"><?php echo JText::_("MOD_SENDY_SUBSCRIBER_SUBSCRIBE");?></button>
		</div>

		<input type="hidden" name="mid" value="<?php echo $this->module->id; ?>" />
		<?php echo JHtml::_('form.token');?>
	</form>
</div>

<script>
	jQuery('form[id="sendy-subscribe-<?php echo $this->module->id; ?>"]').submit(function (form) {
		var formId = '#' + form.target.id;
		var formData = jQuery(formId).serialize();
		var email = jQuery(formId + ' input[name=email]').val();

		jQuery.ajax({
			url: '<?php echo JUri::root(); ?>index.php?option=com_ajax&format=html&plugin=sendy',
			data: formData,
			type: "POST",
			dataType: 'text json',
			success: function (ret) {
				if (ret.success == true) {
					jQuery("#sendy-<?php echo $this->module->id ?>").fadeOut('500');
					jQuery("#sendy-<?php echo $this->module->id ?>").html(ret.html).fadeIn('1000');
				}
				else {
					jQuery("#sendy-subscribe-<?php echo $this->module->id;?>-error-message").parent().removeClass('hide');
					jQuery("#sendy-subscribe-<?php echo $this->module->id;?>-error-message").fadeOut('fast');
					jQuery("#sendy-subscribe-<?php echo $this->module->id;?>-error-message").text(ret.errorMessage);
					jQuery("#sendy-subscribe-<?php echo $this->module->id;?>-error-message").fadeIn('slow');
				}
			}
		});
		return false; /*Don't actually submit the form*/
	});
</script>

<style>
	.modSendy input[type="text"] {width: 100%;}
</style>
