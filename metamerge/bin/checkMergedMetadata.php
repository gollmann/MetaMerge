#!/usr/bin/env php
<?php

define('SIMPLESAMLPATH', dirname(dirname(dirname(dirname(__FILE__)))));
ini_set('error_level', E_ALL & E_STRICT);
ini_set('display_errors', true);

if(!($gen = @file_get_contents(SIMPLESAMLPATH.'/metadata-generated/saml20-sp-merged.php'))) {
	echo("failed to get generated file's contents\n");
	exit(2);
}

$gen = preg_replace('/<\?php/', '', $gen);
$gen = preg_replace('/\?>/', '', $gen);

if(($ret = @eval($gen) === false)) {
	echo("parse error in generated code\n");
	exit(2);
}

function _raise_error($msg) {
	echo($msg."\n");
	exit(3);
}

if(!isset($metadata))
	_raise_error('metadata variable not set');

if(!is_array($metadata))
	_raise_error('metadata variable is not an array');

if(sizeof($metadata) < 10)
	_raise_error('metadata array holds less than 10 items');

foreach($metadata as $k => $m) {
	if(!is_array($m))
		_raise_error('metadata item '.((string)$k).' is not an array');
	if(empty($m['entityid']) || !is_string($m['entityid']))
		_raise_error('metadata item '.((string)$k).' has empty or non-string \'entityid\' member');
	if(empty($m['authproc']) || !is_array($m['authproc']))
		_raise_error('metadata item '.((string)$k).' has empty or non-array \'authproc\' member');
	if(empty($m['attributes']) || !is_array($m['attributes']))
		_raise_error('metadata item '.((string)$k).' has empty or non-array \'attributes\' member');
}

exit(0);
