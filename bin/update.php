#!/usr/bin/env php
<?php

require 'support.inc.php';

$code = 0;
$groups = (array)$composer_obj->orange->groups;

$commands = array_keys($groups);

$command = get_arg1(false);

if (!$command) {
	e('Please provide option set '.implode(', ',$commands));
	die();
}

$commands = $groups[$command];

foreach ($commands as $cli) {
	$cli = str_replace(['{ROOTPATH}','{ESCROOTPATH}'],[ROOTPATH,ESCROOTPATH],$cli);

	passthru($cli,$exit_code);
	
	if ($exit_code > 0) {
		break;
	}
}

