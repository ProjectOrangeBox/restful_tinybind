#!/usr/bin/env php
<?php

passthru('sudo echo');

define('ROOTPATH',realpath(__DIR__.'/../'));

echo 'Cleaning Cache Folder'.chr(10);

delete_files(ROOTPATH.'/var/cache');

function delete_files($searchDirectory) {
	foreach (glob(escapeshellcmd($searchDirectory).'/*') as $folderitem) {
		if (is_dir($folderitem)) {
			delete_files($folderitem);
		} else {
			passthru('sudo rm -f '.s($folderitem));

			echo $folderitem.chr(10);
		}
	}
}

function s($input) {
	return str_replace(' ','\ ',$input);
}
