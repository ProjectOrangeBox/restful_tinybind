#!/usr/bin/env php
<?php

require 'support.inc.php';

$option = get_arg1(false);

if (!@isset($composer_obj->orange->clean->$option)) {
	e('Clean option not found');
	e('Options include:');
	foreach ((array)$composer_obj->orange->clean as $o=>$x) {
		e('   '.$o);
	}
	die();
}

foreach ($composer_obj->orange->clean->$option as $cli) {
	shell('sudo '.$cli);
}
