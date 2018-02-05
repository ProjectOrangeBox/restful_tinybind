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

echo chr(10).'Setting the Default Permissions'.chr(10);

/* files */
passthru('sudo find '.ROOTPATH.' -type f | sudo xargs chmod 664');

/* directories */
passthru('sudo find '.ROOTPATH.' -type d | sudo xargs chmod 775');

/* change the others back */
echo chr(10).'Set Permissions Based on Composer Config'.chr(10);

if (isset($composer_obj->orange->permission)) {

	$permissions = $composer_obj->orange->permission;

	if (is_array($permissions)) {
		foreach ($permissions[0] as $filename=>$filemode) {
			$filename = trim($filename,'/');

			if (substr($filename,0,1) !== '#') {
				echo ROOTPATH.'/'.$filename.' >> '.$filemode.chr(10);

				/* does this folder exist? */
				if (!file_exists(ROOTPATH.'/'.$filename)) {
					passthru('sudo mkdir -p '.$filemode.' '.ROOTPATH.'/'.$filename);
				}

				$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ROOTPATH.'/'.$filename));

				foreach($iterator as $item) {
					$bn = basename($item);
					
					if ($bn != '.' && $bn != '..') {
						passthru('sudo chmod '.$filemode.' '.$item);
					}
				}
			}
		}
	}
}
