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

$options = implode('|',$_SERVER['argv']);

$reverse = (strpos($options,'-r') !== false);
$copy = (strpos($options,'-c') !== false);

if ($copy) {
	echo 'Copying Folders'.chr(10);
} else {
	echo 'Symlink Folders'.chr(10);
}

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
				
				if ($copy) {
					passthru('sudo cp -R '.s(ROOTPATH.$private).' '.s(ROOTPATH.$public));
				} else {
					relative_symlink($private, $public);
				}
			}
		}
	}
}

/* figure out relative path */
function relative_symlink($target, $link) {
	/* remove the link that might be there */

	/* let's make sure the rootpath is NOT there since we add it */
	if (substr($link, 0, strlen(ROOTPATH)) == ROOTPATH) {
		$link = substr($link, strlen(ROOTPATH));
	}

	if (substr($target, 0, strlen(ROOTPATH)) == ROOTPATH) {
		$target = substr($target, strlen(ROOTPATH));
	}

	/* remove it if it's already there */
	//@unlink(ROOTPATH . $link);
	passthru('sudo rm -fdr '.s(ROOTPATH.$link));

	/* create it */
	passthru('sudo ln -s '.s(ROOTPATH.$target).' '.s(ROOTPATH.$link));
}

function s($input) {
	return str_replace(' ','\ ',$input);
}
