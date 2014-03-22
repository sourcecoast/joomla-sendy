<?php
/**
 * @package        Sendy Module
 * @copyright (C) 2014 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

?>
<div class="sendy subscribe" id="sendy-<?php echo $this->module->id; ?>">
    <div class="intro">
        <?php echo $this->params->get('introtext'); ?>
    </div>
    <form id="sendy-subscribe-<?php echo $this->module->id; ?>">
        <input type="text" name="email" placeholder="Email Address" />
        <button type="submit" name="Sign up" class="btn span3">Sign Up</button>
        <input type="hidden" name="mid" value="<?php echo $this->module->id; ?>" />
    </form>
</div>

<script>
    jQuery('form[id="sendy-subscribe-<?php echo $this->module->id; ?>"]').submit(function (form)
    {
        var formId = '#' + form.target.id;
        var email = jQuery(formId + ' input[name=email]').val();
        var url = 'email=' + email + '&<?php echo JSession::getFormToken(); ?>=1&mid=<?php echo $this->module->id; ?>';
        jQuery("#sendy-<?php echo $this->module->id ?>").fadeOut('500');
        jQuery.ajax({
            url: '<?php echo JURI::base(); ?>modules/mod_sendy/ajax/subscribe.php',
            data: url,
            type: "POST",
            dataType: 'text json',
            success: function (ret)
            {
                if (ret.success == true)
                {
                    jQuery("#sendy-<?php echo $this->module->id ?>").html(ret.html).fadeIn('1000');
                }
                else
                {
                    jQuery("#sendy-<?php echo $this->module->id ?>").html("There was an error processing your subscription.").fadeIn('1000');
                }
            }
        });
        return false; // Don't actually submit the form
    });
</script>