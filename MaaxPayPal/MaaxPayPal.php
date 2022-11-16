<?php
/**
Title: WISECP PayPal Smart Payment Gateway

Package: MaaxPayPal
Version: 1.0.10
Author: Alex Mathias / Nadal Kumar / Peter Walker
Website: //github.com/WebsiteDons/wisecp-module-paypal-smart-checkout
Copyright: Copyright (C) 2009-2022 WebsiteDons.com
License: GNU/GPL: //www.gnu.org/copyleft/gpl.html
Description: Allow modal window form sourced from PayPal in iframe to avoid sending buyer to PayPal website
*/

defined('CORE_FOLDER') || exit('WebsiteDons.com');

include_once 'functions.php';
include_once 'class.form.php';
if( !class_exists('Money') ) {
	include_once HELPER_DIR.'money.php';
}

class MaaxPayPal extends PaymentGatewayModule
{
	public $checkout_id,$checkout;
	public $name,$commission=true;
	public $config=[],$lang=[],$page_type = 'in-page',$callback_type='server-sided';
	public $payform=false;

	function __construct() 
	{
		$this->filemeta = makeobj(getFileMeta(__DIR__.'/'.__CLASS__.'.php',2,8));
		$this->config_file 	= __DIR__.'/config.php';
		$this->name			= __CLASS__;
		$this->makeConfig();
		
		$this->config		= Modules::Config('Payment',__CLASS__);
		$this->setting		= (isset(makeobj($this->config)->settings) ? makeobj($this->config)->settings : []);
		$this->lang			= Modules::Lang('Payment',__CLASS__);
		$this->currency		= Money::Currency(Config::get('general/currency'))['code'];
		
		$this->payform		= __DIR__.'/pages/payform';
		$this->xmlform		= __DIR__.'/form/form';
		$this->actionUrl 	= getvar(Controllers::$init->getData('links')['controller']);
		$this->notify_url	= Controllers::$init->CRLink('payment',[__CLASS__,$this->get_auth_token(),'callback']);
		$this->success_url	= Controllers::$init->CRLink('pay-successful');
		$this->failed_url	= Controllers::$init->CRLink('pay-failed');
		
		// move bootstrap to core resources folder
		if( !file_exists(ROOT_DIR.'resources/bootstrap') ) {
			doUnzip(__DIR__.'/assets/bootstrap.zip', ROOT_DIR.'resources');
			rename(ROOT_DIR.'resources/bootstrap-5.2.2-dist', ROOT_DIR.'resources/bootstrap');
		}

		parent::__construct();
	}
	
	// create config.php if missing
	public function makeConfig() 
	{
		if( !file_exists($this->config_file) ) {
$make_config = "<?php 
return [
	'meta'     => [
		'name'    => '".__CLASS__."',
		'version' => '".$this->filemeta->Version."',
		'logo'    => 'assets/images/pplogo.png'
	],
	'settings' => [
	'funding_type' => ['venmo', 'paylater']
	]
];
";
			file_put_contents($this->config_file, $make_config);
		}
	}

	public function get_auth_token() {
		$syskey = Config::get('crypt/system');
		$token  = md5(Crypt::encode('MaaxPayPal-Auth-Token='.$syskey,$syskey));
		
		return $token;
	}

	public function commission_fee_calculator($amount) {
		$rate = $this->get_commission_rate();
		
		if( !$rate ) 
			return 0;
		$calculate = Money::get_discount_amount($amount,$rate);
		
		return $calculate;
	}

	public function get_commission_rate() {
		return $this->setting->commission_rate;
	}

	public function cid_convert_code($id=0) {
		Helper::Load('Money');
		$currency = Money::Currency($id);
		if( $currency ) 
			return $currency['code'];
		
		return false;
	}

	public function get_ip() {
		return UserManager::GetIP();
	}
	
	public function set_checkout($checkout) {
		$this->checkout_id = $checkout['id'];
		$this->checkout    = $checkout;
	}


	public function payment_result()
	{
		$checkout_id = (!empty($this->checkout_id) ? $this->checkout_id:'');
		$error		= false;
		$txn_id		= 0;

		if( $error ) {
			return [
			'status' => 'ERROR',
			'status_msg' => 'an error occurrred',
			];
		}

		$checkout = Basket::get_checkout($checkout_id);

		if( !$checkout ) {
			return [
			'status' => 'ERROR',
			'status_msg' => Bootstrap::$lang->get('errors/error6',Config::get('general/local')),
			];
		}

		$this->set_checkout($checkout);

		Basket::set_checkout($checkout_id,['status' => 'paid']);

		return [
		'status' => 'SUCCESS',
		'checkout' => $checkout,
		'status_msg' => '',
		];
	}
	
	// method to output values from checkout object
	public function val() {
		$val = makeobj($this->checkout);
		
		$index = [
		'paytotal' => Money::formatter_symbol($val->data->total, $this->currency)
		];
		
		return makeobj($index);
	}
	
	
	## PAYPAL SMART CHECOUT
	/*
	https://developer.paypal.com/demo/checkout/#/pattern/server
	https://developer.paypal.com/api/rest/
	https://github.com/paypal/ipn-code-samples/tree/master/php
	https://developer.paypal.com/api/nvp-soap/ipn/ht-ipn/
	https://developer.paypal.com/sdk/js/reference/
	
	sandbox test card generated at Payal
	https://developer.paypal.com/dashboard/creditCardGenerator
	4032034025696660
	09/2024
	005
	*/
	public function smartCheckout()
	{
		$config		= $this->setting;
		$chk		= makeobj($this->checkout);
		$total		= (!empty($chk->data->total) ? $chk->data->total:'0.00');
		$invcookie=false;
		
		$clid = (!empty($config->client_id) ? $config->client_id : '');
		$clid_sb = (!empty($config->sandbox) ? $config->sandbox : '');
		
		$sbip='';
		if( $config->enable_sandbox && !empty($config->sandbox_ip) ) {
			$getip = explode(',', $config->sandbox_ip);
			$sbip = (isset($getip[0]) ? $getip[0]:'');
		}
		// setup http query for paypal sdk
		$dis_fund = [];
		foreach(['card','paylater','venmo'] as $def) {
			if( !in_array($def,(array)$config->funding_type) )
				$dis_fund[] = $def;
		}
		$funding = (!empty($config->funding_type) ? '&enable-funding='.implode(',',(array)$config->funding_type) :'');
		$defund = (!empty($dis_fund) ? '&disable-funding='.implode(',',$dis_fund):'');
		$urlq = [
		'client-id' => (!empty($clid_sb) && !empty($sbip) && $_SERVER['REMOTE_ADDR'] == $sbip ? $clid_sb : $clid),
		'currency' => $this->currency
		];
		
		$html = '
		<div id="smart-button-container">
		<div style="text-align: center;">
		<div id="paypal-button-container"></div>
		</div>
		</div>';

		$js = '
		<script src="https://www.paypal.com/sdk/js?'.http_build_query($urlq).$funding.$defund.'"></script>
		<script>
		
		function initPayPalButton() 
		{
			paypal.Buttons({
				style: {
				shape: "'.$config->btnstyle.'",
				color: "'.$config->btncolor.'",
				layout: "'.$config->btnalign.'",
				label: "'.$config->btnlabel.'",
				height: '.$config->btnheight.',
				tagline: false
				},

				createOrder: function(data, actions) {
					return actions.order.create({
						purchase_units: [{
							"amount":{
								"currency_code":"'.$this->currency.'",
								"value": '.$total.'
							}
						}]
					});
				},

				onApprove: function(data, actions) {
					return actions.order.capture().then(function(orderData) {

						const transaction = orderData.purchase_units[0].payments.captures[0];
						const element = document.getElementById("paypal-button-container");
						element.innerHTML = "";
						
						window.location.replace("'.$this->notify_url.'");
						//actions.redirect("'.$this->notify_url.'");
						element.innerHTML = "'.$this->lang['pp-success-notice'].'";

						// Full available details
						console.log("Capture result", orderData, JSON.stringify(orderData, null, 2));
					});
				},

				onError: function(err) {
				console.log(err);
				}
			}).render("#paypal-button-container");
		}
		initPayPalButton();
		</script>';
		
		return $html . $js;
	}
	
}

