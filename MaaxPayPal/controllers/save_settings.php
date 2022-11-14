<?php 
/**
* PayPal Smart Payment Gateway
*
* @package MaaxPayPal
* @author Alex Mathias / Nadal Kumar / Peter Walker
* @copyright (C) 2009-2022 WebsiteDons.com
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
*/

defined('CORE_FOLDER') || exit('YourDomain.com');

Helper::Load(['Money']);
$fnames = wcpForm::fnames($module->xmlform);

if( !empty($_POST['module']) && $_POST['module'] == $module->name )
{
	$flds=[];
	foreach($fnames as $fname) {
		$flds[$fname] = Filter::init('POST/'.$fname);
	}

	$sets=[];
	foreach($flds as $k=>$v) {
		$sets['settings'][$k] = $v;
	}

	$config_result = array_replace_recursive($module->config,$sets);
	$array_export = Utility::array_export($config_result,['pwith' => true]);
	$write = FileManager::file_write($module->config_file,$array_export);
	
	$adata = UserManager::LoginData('admin');
	User::addAction($adata['id'],'alteration','changed-payment-module-settings',[
	'module' => $module->name,
	'name'   => $module->lang['name']
	]);
}

echo Utility::jencode(['status' => 'successful','message' => $module->lang['success1']]);