<?php 
/**
* PayPal Smart Payment Gateway
*
* @package MaaxPayPal
* @author Alex Mathias / Nadal Kumar / Peter Walker
* @copyright (C) 2009-2022 WebsiteDons.com
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
* @Description: The HTML form is loaded from the included _vars.php using variable $fields
*/

defined('CORE_FOLDER') || exit('YourDomain.com');

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

		<button id="<?php echo $module->name; ?>_submit" class="btn btn-primary btn-block"><?php echo $module->lang['save-button']; ?></button>
		<button class="btn btn-secondary" id="<?php echo $module->name; ?>_test"><?php echo $module->lang['test-connection']; ?></button>
	</form>
</div>

<script src="<?php echo $module->url.'/pages/settings.js?v='.ft($module->dir.'pages/settings.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo $module->url; ?>/assets/maaxpaypal.css?v=<?php echo ft($module->dir.'/assets//maaxpaypal.css'); ?>" />
<link rel="stylesheet" href="<?php echo lib()->bs; ?>/css/bootstrap.min.css?v=5.2" />
<script src="<?php echo lib()->bs; ?>/js/bootstrap.min.js?v=5.2"></script>