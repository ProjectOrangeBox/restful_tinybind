#!/usr/bin/env php
<?php

passthru('sudo echo');

define('ROOTPATH', str_replace(' ','\\ ',realpath(__DIR__.'/../')));

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

echo chr(10).'Relink Symbolic Links'.chr(10);

if (isset($composer_obj->orange->symlink)) {

	$links = $composer_obj->orange->symlink;

	if (is_array($links)) {
		foreach ($links[0] as $public => $private) {
			if (substr($public,0,1) !== '#') {
				echo $private.' >> '.$public.chr(10);

				relative_symlink($private, $public);
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
	passthru('sudo rm -f '.ROOTPATH . $link);

	/* create it */
	passthru('sudo ln -s '.ROOTPATH . $target.' '.ROOTPATH . $link);
}
