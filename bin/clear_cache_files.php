#!/usr/bin/env php
<?php

passthru('sudo echo');

define('ROOTPATH',realpath(__DIR__.'/../'));

echo 'Cleaning Cache Folder'.chr(10);

delete_files(ROOTPATH.'/var/cache');
delete_files(ROOTPATH.'/var/uploads');
delete_files(ROOTPATH.'/var/downloads');

function delete_files($searchDirectory) {
	foreach (glob(escapeshellcmd($searchDirectory).'/*') as $folderitem) {
		if (is_dir($folderitem)) {
			delete_files($folderitem);
		} else {
			echo $folderitem.chr(10);
			passthru('sudo rm -f '.s($folderitem));
		}
	}
}

function s($input) {
	return str_replace(' ','\ ',$input);
}
