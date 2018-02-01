#!/usr/bin/env php
<?php

if (strtolower($_SERVER['USER']) != 'root') {
	die('Please run as super user'.chr(10));
}

define('ROOTPATH', realpath(__DIR__.'/../'));

$filename = ROOTPATH.'/composer.json';

$composer = get_composer_object($filename);

fix_file_permissions($composer);
fix_symlink($composer);

function fix_file_permissions($composer_obj) {
	echo chr(10).'Setting the Default Permissions'.chr(10);

	/* files */
	@system('find "'.ROOTPATH.'" -type f | xargs chmod 664');

	/* directories */
	@system('find "'.ROOTPATH.'" -type d | xargs chmod 775');

	/* change the others back */
	echo chr(10).'Set Permissions Based on Composer Config'.chr(10);

	if (isset($composer_obj->orange->permission)) {

		$permissions = $composer_obj->orange->permission;

		if (is_array($permissions)) {
			foreach ($permissions[0] as $filename=>$filemode) {

				if (substr($filename,0,1) !== '#') {
					echo $filename.' >> '.$filemode.chr(10);

					/* does this folder exist? */
					if (!file_exists(ROOTPATH.'/'.$filename)) {
						@mkdir(ROOTPATH.'/'.$filename, octdec($filemode), true);
					}

					$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ROOTPATH.'/'.$filename));

					foreach($iterator as $item) {
				    @chmod($item, octdec($filemode));
					}
				}
			}
		}
	}
	
	echo chr(10).'Set Bin folder scripts to executables'.chr(10);
	@system('chmod 755 "'.ROOTPATH.'/bin/*"');

}

function fix_symlink($composer_obj) {
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

}

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
	@unlink(ROOTPATH . $link);

	/* create it */
	return symlink(ROOTPATH . $target, ROOTPATH . $link);
}

function get_composer_object($composer_file) {
	echo 'Using Composer File '.$composer_file.chr(10);

	if (file_exists($composer_file)) {
		$composer_obj = json_decode(file_get_contents($composer_file));

		if ($composer_obj === null) {
			die('composer.json malformed'.chr(10));
		}
	} else {
		die('can not locate composer.json as "'.$composer_file.'"'.chr(10));
	}

	return $composer_obj;
}
