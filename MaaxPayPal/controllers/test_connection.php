<?php if(!defined("CORE_FOLDER")) die();

$fnames = wcpForm::fnames(dirname(__DIR__).'/form/form');

Helper::Load(['Money']);
$lang = $module->lang;
$config = $module->config;

$flds=[];
foreach($fnames as $fname) {
	$flds[$fname] = Filter::init('POST/'.$fname);
}

$remove_auth = false;
$sets = [];

foreach($flds as $k=>$v) {
	$sets['settings'][$k] = $v;
}

dump($flds);
