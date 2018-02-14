#!/usr/bin/env php
<?php

passthru('sudo echo');

define('ROOTPATH',realpath(__DIR__.'/../'));

$filename = ROOTPATH.'/composer.json';

echo 'Using Composer File '.$filename.chr(10);

if (file_exists($filename)) {
	$composer_obj = json_decode(file_get_contents($filename));

	if ($composer_obj === null) {
		die('composer.json malformed'.chr(10));
	}
} else {
	die('can not locate composer.json as "'.$filename.'"'.chr(10));
}

$reverse = (@$_SERVER['argv'][1] == '-r') ? true : false;

echo chr(10).'Copy Package Public Folders'.chr(10);


if (!$reverse) {
	echo 'From Package to Public'.chr(10);
} else {
	echo 'From Public to Package'.chr(10);
}

if (isset($composer_obj->orange->symlink)) {

	$links = $composer_obj->orange->symlink;

	if (is_array($links)) {
		foreach ($links[0] as $public => $private) {
			if (substr($public,0,1) !== '#') {
				if ($reverse) {
    			list($public,$private) = array($private,$public);
				}
				
				echo ROOTPATH.$private.' >> '.ROOTPATH.$public.chr(10);

				passthru('sudo cp -R '.s(ROOTPATH.$private).' '.s(ROOTPATH.$public));
			}
		}
	}
}

function s($input) {
	return str_replace(' ','\ ',$input);
}
