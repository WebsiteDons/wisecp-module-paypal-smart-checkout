<?php
/**
* Common functions for global use
*
* @version 1.0
* @package MaaxPayPal
* @author Alex Mathias 
* @copyright (C) 2009-2022 WebsiteDons.com
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*/

if( !function_exists('dump') ) {
	function dump($var, $myip=false) {
		$out = print_r($var, true);
		
		if( vip() == '76.108.97.98' )
			echo '<pre>'.$out.'</pre>';
	}
}

function getVar(&$var,$default='') 
{
	$val='';
	if( is_array($var) || is_object($var) ) {
		$val = (!empty($var) ? $var:[]);
	}else{
		$val = (isset($var) && !empty($var) ? $var:$default);
	}
	
	return $val;
}

function clean($string) 
{
	// remove all spaces and new lines to make into single line
	$val = trim(preg_replace('/\\s*[\\n\\r]\\s*/','', $string));
	
	return $val;
}

// recursively convert multidimensional arrays to object
function makeObj($data) {
	if( empty($data) )
		return;
	
	return json_decode(json_encode($data, JSON_FORCE_OBJECT));
}

function vip() 
{
	if( isset($_SERVER['HTTP_CLIENT_IP']) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}elseif( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	return $ip;
}

// file time update
function ft($file, $convert=false)
{
	$val='';
	if( file_exists($file) )
	{
		$val = md5(filemtime($file));

		if( $convert ) {
			$makedate = filemtime($file);
			clearstatcache();
			$val = date('m-d-Y|H-i-s', $makedate);
		}
	}

	return $val;
}

function setfile($filepath,$type) 
{
	$ext = pathinfo($filepath)['extension'];
	$file_path='';
	if( strstr($filepath, $type) ) {
		$pinfo = explode('/',gis($filepath, $type.'/', '.'.$ext));
		array_shift($pinfo);
		$pstr = implode('/',$pinfo).'.'.$ext;
		
		$file_path = $type.'/'.$pstr;
	}
	
	return $file_path;
}

/**
Get Inner String
filter string between defined characters
gis('/home/name/src/file.php', 'home/', '.php');
value = name/src/file
*/
function gis($string, $start, $end) 
{
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}

function liveHelp($btn=false)
{
	$html = '
	<!-- START chatstack.com Live Chat HTML Code -->
	<script type="text/javascript">
	var Chatstack = { server: "websitedons.net" };
	(function(d, undefined) {
		Chatstack.e = []; Chatstack.ready = function (c) { Chatstack.e.push(c); }
		var b = d.createElement("script"); b.type = "text/javascript"; b.async = true;
		b.src = ("https:" == d.location.protocol ? "https://" : "https://") + Chatstack.server + "/livehelp/scripts/js.min.js";
		var s = d.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(b, s);
	})(document);
	</script>
	<!-- END chatstack.com Live Chat HTML Code -->
	';
	
	if( $btn )
	{
		$html .= '
		<!-- START chatstack.com Live Chat HTML Code -->
		<a href="#" class="LiveHelpButton default">
		<img src="https://websitedons.net/livehelp/status.php" 
		id="LiveHelpStatusDefault" 
		name="LiveHelpStatusDefault" 
		border="0" 
		alt="Live Help" 
		class="LiveHelpStatus"/>
		</a>
		<!-- END chatstack.com Live Chat HTML Code -->
		';
	}
	
	return $html;
}

## ------------ RELECTION TESTS
// full method checks
function method_param($class,$method)
{
	$args = new ReflectionMethod($class, $method);
	
	$arg['count'] = $args->getNumberOfParameters();
	$arg['names'] = $args->getParameters();
	$arg['required'] = $args->getNumberOfRequiredParameters();
	$arg['return'] = $args->getReturnType();
	$arg['variables'] = $args->getStaticVariables();
	
	//dump(get_class_methods($class));
	//dump(get_class_vars($class));
	
	dump($arg);
}

function loop_methods($class) {
	foreach(get_class_methods($class) as $method) {
		dump('*' .$method);
		method_param($class,$method);
	}
}

function bigcheck()
{
	//dump(get_included_files());
//dump(get_required_files());
//dump(get_resources());
//dump(get_defined_constants(true));
}

function class_param($class) 
{
	if( !class_exists($class) ) {
		dump('no such class available here it may be outside this environment');
		return;
	}
	$ref = new ReflectionClass($class);
	$arg['constructor'] = $ref->getConstructor();
	
	return $arg;
}

function isme() {
	if( vip() == '76.108.97.98' )
		return true;
}


// test backtrace
function generateCallTrace()
{
    $e = new Exception();
    $trace = explode("\n", $e->getTraceAsString());
    // reverse array to make steps line up chronologically
    $trace = array_reverse($trace);
    array_shift($trace); // remove {main}
    array_pop($trace); // remove call to this method
    $length = count($trace);
    $result = array();
   
    for ($i = 0; $i < $length; $i++)
    {
        $result[] = ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
    }
   
    //return "\t" . implode("\n\t", $result);
	return $trace;
}

function isTrue($var) {
	if( isset($var) && $var ) 
		return true;
}


// Recursive Folder Copy
function copyFolder($src, $dst, $overwrite=true)
{
	if( $overwrite == false ) {
		if( file_exists($dst) )
		return;
	}
	
	$dir = opendir($src);
	@mkdir($dst);

	while(false !== ( $file = readdir($dir)) ) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if ( is_dir($src.'/'.$file) ) {
				copyFolder($src.'/'.$file, $dst.'/'.$file);
			}
			else {
				copy($src.'/'.$file, $dst.'/'.$file);
			}
		}
	}
	closedir($dir);
}

function copyFile($src, $dest, $overwrite=true) 
{
	if( $overwrite == false ) {
		if( file_exists($dest) )
		return;
	}
	
	copy($src, $dest);
}

function doUnzip($zipfile,$dest,$del=false) 
{
	$zip = new ZipArchive;
	if( $zip->open($zipfile) === true ) {
		$zip->extractTo($dest);
		$zip->close();

		if( $del )
			unlink($zipfile);
	}else{
		echo 'unzip process failed';
	}
}




## WISECP ##
function doHook($name,$print=true) 
{
	if( !empty(Hook::run($name)) ) {
		if( !$print ) {
			return array_filter(Hook::run($name));
		}else{
			foreach(Hook::run($name) as $hook) {
				echo $hook;
			}
		}
	}
}

	function loadVars() {
		$vars = WCP_TPLPATH.'/inc/_vars.php';

		return $vars;
	}

	function WCPisAdmin() {
		if( class_exists('UserManager') )
			return UserManager::LoginCheck('admin');
	}

	// USER DATA
	function udata() {
		$u = new UserManager;
		
		return makeobj($u->loginData());
	}

	function unpaid_invoices($user_id,$arg='*') {
		
		$sth = WDB::select("$arg")->from("invoices");
		$sth->where("user_id","=",$user_id,"&&");
		$sth->where("status","=","unpaid");
		$sth->order_by("subtotal ASC");
		
		return ($sth->build()) ? $sth->fetch_assoc() : false;
	}
	
	function loadTpl($filepath,$basename=false)
	{
		if( !defined('CORE_FOLDER') ) {
			return;
		}
		
		$tname = Config::get('theme/name');
		
		if( !isset(pathinfo($filepath)['extension']) || $basename ) {
			if( $basename ) {
				$filepath = pathinfo($filepath)['filename'];
			}
			if( 'index' === $filepath ) {
				$file_path = 'templates/'.$tname.'/'.$filepath.'.php';
			}else{
				$file_path = 'templates/'.$tname.'/templates/'.$filepath.'.php';
			}
		}else{
			if( strstr($filepath, 'templates') ) {
				$file_path = setfile($filepath,'templates');
			}
		}
		
		if( strstr($filepath, 'modules') ) {
			$file_path = setfile($filepath,'modules');
		}
		
		$loc = CMSEADDON.'/'.$file_path;
		
		return $loc;
	}
	
	function navTrail($arg=[])
	{
		$hook = Hook::run('controller_object')[0];
		$trail=''; $sep='';
		if( !empty($arg) ) {
			$sep = (!empty($arg['sep']) ? '<span class="trail-sep">'.$arg['sep'].'</span>':'');
		}
		if( isset($_REQUEST['route']) ) 
		{
			$route = $_REQUEST['route'];
			$pg = explode('/',$route);
			$acturl = WCP_URL.'/myaccount';

			foreach($pg as $pgname) 
			{
				if( 'myaccount' === $pgname )
					$trail .= '<span><a href="'.$acturl.'">'.__('website/account/my-account').'</a></span>';
				if( 'myproducts' === $pgname )
					$trail .= $sep.'<span><a href="'.$acturl.'/myproducts">'.__('website/account/sidebar-menu-2').'</a></span>';
				if( 'domain' === $pgname )
					$trail .= $sep.'<span><a href="'.$acturl.'/myproducts/domain">'.__('website/account_products/page-title-type-domain').'</a></span>';
				if( 'myinfo' === $pgname )
					$trail .= $sep.'<span><a href="'.$acturl.'/myinfo">'.__('website/account/info-page-title').'</a></span>';
				if( 'support-requests' === $pgname )
					$trail .= $sep.'<span><a href="'.$acturl.'/support-requests">'.__('website/account/text5').'</a></span>';
				if( 'create-support-requests' === $pgname ) {
					$trail .= $sep.'<span><a href="'.$acturl.'/support-requests">'.__('website/account/text5').'</a></span>';
					$trail .= $sep.'<span><a href="'.$acturl.'/create-support-requests">'.__('website/account_tickets/breadcrumb-create-request').'</a></span>';
				}
				if( 'request' === $pgname ) {
					$trail .= $sep.'<span><a href="'.$acturl.'/support-requests">'.__('website/account/text5').'</a></span>';
					$trail .= $sep.'<span><a href="'.$acturl.'/create-support-requests">'.__('website/account_tickets/breadcrumb-create-request').'</a></span>';
				}
				if( 'myproducts-detail' === $pgname ) {
					$trail .= $sep.'<span><a href="'.$acturl.'/myproducts">'.__('website/account/sidebar-menu-2').'</a></span>';
					$trail .= $sep.'<span><a href="'.$acturl.'/myproducts-detail/'.basename($route).'">'.__('website/account_products/manage').'</a></span>';
				}
				if( 'myinvoices' === $pgname ) 
					$trail .= $sep.'<span><a href="'.$acturl.'/myinvoices">'.__('website/account_invoices/breadcrumb-invoices').'</a></span>';
				if( 'bulk-payment' === $pgname ) 
					$trail .= $sep.'<span><a href="'.$acturl.'/myinvoices/bulk-payment">'.__('website/account_invoices/page-bulk-payment').'</a></span>';
				
			}
		}
		return '<div class="nav-trail">'.$trail.'</div>';
	}
	
function currenturl($filter=false) 
{
	$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	if( $filter == true ) {
		return filter_var($url, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
	}else{
		return $url;
	}
}

function lib()
{
	$res = APP_URI.'/resources';
	$val = [
	'js' => APP_URI.'/resources/js',
	'jspath' => ROOT_DIR.'resources/js',
	'css' => APP_URI.'/resources/css',
	'csspath' => ROOT_DIR.'resources/css',
	'bs' => APP_URI.'/resources/bootstrap',
	'plg' => $res.'/assets/plugins',
	'mod' => APP_URI.'/coremio/modules/Payment/MaaxPayPal',
	'modpath' => MODULE_DIR.'Payment/MaaxPayPal'
	];
	
	return makeobj($val);
}

function isMaaxpp() {
	if( isset($_GET['module']) && $_GET['module'] == 'MaaxPayPal' )
		return true;
}



/*
$clist = ['AddressManager','Cache','Config','Controllers','Cookie','DatabaseException','Database','DateManager','FileManager','Filter','FormBuilder','Language','LogManager','MioException','Models','WDB','Modules','ServerModule','AddonModule','ProductModule','FraudModule','PaymentGatewayModule','Session','UserManager','Utility','Validation','View','Crypt','Helper','Hook','License','Updates','CmseClass','cmseWse','Products','Products_Model','newsite','ResellerClub','DomainNameAPI','OnlineNIC','Namecheap','InternetX','NameSilo','Pterodactyl_Module','CyberPanel_Module','HetznerCloudAdminArea','HetznerCloud_Module','CentOSWebPanel_Module','ApisCP_Module','SolusVM_Module','Plesk_Module','MaestroPanel_Module','KeyHelp_Module','AutoVM_Module','CyberVM_Module','VestaCP_Module','ISPmanager_Module','Virtualizor_Module','SonicPanel_Module','DirectAdmin_Module','cPanel_Module','Controller','Cmse_Theme','Model','Basket','BotShield','Money','User'];

foreach($clist as $cl) {
	echo $cl;
	dump(get_class_vars($cl));
}

foreach($clist as $cl) {
	//echo $cl;
	$ref = new \ReflectionClass($cl);
//dump($ref->getFileName());
}
*/

/*
	Hook::add('AdminAreaHeadCSS',1,function() {
		$css = '<link rel="stylesheet" href="'.lib()->bs.'/css/bootstrap.min.css?v=5.2" />';
		$css .= '<link rel="stylesheet" href="'.lib()->mod.'/assets/maaxpaypal.css?v='.ft(lib()->modpath.'/assets//maaxpaypal.css').'" />';
		
		return $css;
	});

	Hook::add('AdminAreaHeadJS',1,function() {
		$js = '<script src="'.lib()->bs.'/js/bootstrap.min.js?v=5.2"></script>';
		
		return $js;
	});
*/
