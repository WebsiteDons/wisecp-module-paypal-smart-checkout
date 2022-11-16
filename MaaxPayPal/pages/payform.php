<?php 
/**
* PayPal Smart Payment Gateway
*
* @package MaaxPayPal
* @author Alex Mathias / Nadal Kumar / Peter Walker
* @copyright (C) 2009-2022 WebsiteDons.com
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*/

defined('CORE_FOLDER') || exit('WebsiteDons.com');

$chk = '';
$autobill	= (isset($chk->pp_subscription) ? true: false);
$subreq 	= (isset($module->setting->force_subscription) ? true:false);

// display paypal sandbox test card value when define IP is set
$sandbox_card='';
if( $module->setting->enable_sandbox && !empty($module->setting->sandbox_ip) ) 
{
	list($sbip,$sbcard,$sbexp,$sbpin) = explode(',',$module->setting->sandbox_ip);
	
	if( empty($sbcard) )
		return;
	
	if( $_SERVER['REMOTE_ADDR'] == $sbip ) {
		$sandbox_card = '
		<div class="sbtest">
		<h5>PayPal sandbox test card</h5>
		<div>
		<span><input type="text" value="'.$sbcard.'" readonly /></span>
		<span><input type="text" value="'.$sbexp.'" readonly /></span>
		<span><input type="text" value="'.$sbpin.'" readonly /></span>
		</div>
		</div>';
	}
}

echo $sandbox_card;

?>

<h5 class="center"><?php echo $module->lang['pay-info1']; ?></h5>
<script>
jQuery(function($) {
	console.log($("#total_fee").text());
});
</script>

<?php
if( $autobill )
{
	?>
	<div id="PayOptions" class="center">
		<?php if( $subreq ) { ?>
			<script type="text/javascript">
			$(document).ready(function(){
				<?php echo $module->name; ?>Redirect("subscription");
			});
			</script>
			<a class="paypalbtn" href="javascript:void 0;" onclick="<?php echo $module->name; ?>Redirect('subscription');">
			<img title="<?php echo $module->lang['pay-with-subscription']; ?>" src="assets/images/paypal-subscribe.svg" />
			</a>
		<?php } ?>
		<?php echo $module->smartCheckout(); ?>
	</div>

	<script type="text/javascript">
	function <?php echo $module->name; ?>Redirect(type) {
		$("#PayOptions").css("display","none");
		$("#RedirectWrap").css("display","block");

		if( type === "subscription" ) {
			window.location.href = "<?php echo $links['subscription']; ?>";
		}else 
		if( type === "normal" ) {
			setTimeout(function(){
				$("#<?php echo $module->name; ?>Redirect").submit();
			},2000);
		}
	}
	</script>
	<?php
}else{
	echo $module->smartCheckout();
}
