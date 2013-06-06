<?php
class UnknownClass		extends Exception {}
class BadArgument		extends Exception {}
class RessourceNotFound	extends Exception {}

define('FILE_ROOT', dirname(__FILE__).'/..');
set_include_path('.:'.FILE_ROOT.'/private/');
chdir(FILE_ROOT.'/private/');

header('Content-type: text/html; charset=utf-8');
mb_internal_encoding('utf-8');

function generic_autoload($c) {
	$v = ltrim(preg_replace('#([A-Z])#', '/\1', $c), '/');
	if(file_exists($v.'.php'))
		require_once $v.'.php';
}

function getGetter($n) {
	return 'get'.ucfirst(strtolower($n));
}

function array_map_recursive($fn, $arr) {
	$rarr = array();
	foreach ($arr as $k => $v) {
		$rarr[$k] = is_array($v)
		? array_map_recursive($fn, $v)
		: $fn($v);
	}
	return $rarr;
}

function iCpt($reset = null) {
	static $i = 0;
	if($reset !== null)
		$i = $reset;
	return $i++;
}

spl_autoload_register('generic_autoload');
