#!/usr/bin/env php
<?php

require 'support.inc.php';

heading('Setting the Default Permissions');

/* files */
passthru('sudo find '.s(ROOTPATH).' -type f | sudo xargs chmod 664');

/* directories */
passthru('sudo find '.s(ROOTPATH).' -type d | sudo xargs chmod 775');

/* change the others back */
heading('Set Permissions Based on Composer Config');

if (isset($composer_obj->orange->permission)) {

	$permissions = $composer_obj->orange->permission;

	if (is_array($permissions)) {
		foreach ($permissions[0] as $filename=>$filemode) {
			$filename = trim($filename,'/');

			if (substr($filename,0,1) !== '#') {
				heading($filemode.' '.ROOTPATH.'/'.$filename);

				/* does this folder exist? */
				if (!file_exists(ROOTPATH.'/'.$filename)) {
					passthru('sudo mkdir -p '.$filemode.' '.s(ROOTPATH).'/'.$filename);
				}

				passthru('sudo chmod '.$filemode.' '.s(ROOTPATH).'/'.$filename);

				globr(ROOTPATH.'/'.$filename,$filemode);
			}
		}
	}
}

function globr($searchDirectory,$filemode) {
	foreach (glob(escapeshellcmd($searchDirectory).'/*') as $folderitem) {
		if (is_dir($folderitem)) {
			globr($folderitem,$filemode);
		} else {
			heading($filemode.' '.$folderitem);
			
			passthru('sudo chmod '.$filemode.' '.s($folderitem));
		}
	}
}
