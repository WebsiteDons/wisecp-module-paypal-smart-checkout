<?php 
/**
* PayPal Smart Payment Gateway
*
* @version 1.0
* @package MaaxPayPal
* @author Alex Mathias 
* @copyright (C) 2009-2022 WebsiteDons.com
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
* @Description: The HTML form is loaded from the included _vars.php using variable $fields
*/

defined('CORE_FOLDER') || exit('YourDomain.com');

if( $module->isMaaxPayPal ) 
{
	echo '<link rel="stylesheet" href="'.$module->url.'assets/maaxpaypal.css?v='.ft($module->dir.'assets/maaxpaypal.css').'" />';
	// enable Select2 for multiple option select fields
	echo '<script>
	jQuery(function($) { 
	$(".s2-multiple").select2({
		placeholder: "Select",
		allowClear: true
	}); 
	});
	</script>';
}

$setJS = '
<script src="'.$module->url.'/pages/settings.js?v='.ft($module->dir.'pages/settings.js').'"></script>
';
?>

<div class="wd-form">
	<form action="<?php echo $module->actionUrl; ?>" method="post" id="<?php echo $module->name; ?>Form">
		<input type="hidden" name="operation" value="module_controller" />
		<input type="hidden" name="module" value="<?php echo $module->name; ?>" />
		<input type="hidden" name="controller" value="save_settings" />

		<?php 
		/* Method that reduces repetitive HTML for form field writing
		get form field names from XML file at form/form.xml
		fields processed by class.form.php
		*/
		echo wcpForm::fields(['value' => $module->setting, 'xmlform' => $module->xmlform]); ?>

		<button id="<?php echo $module->name; ?>_submit" class="btn"><?php echo $module->lang['save-button']; ?></button>
		<div class="guncellebtn type-contents type-is-subscription">
		<button class="btn" id="<?php echo $module->name; ?>_test">
		<i class="fa fa-plug"></i> <?php echo $module->lang['test-connection']; ?>
		</button>
		</div>
	</form>
</div>

<?php echo $setJS; ?>