#!/usr/bin/env php
<?php

if (strtolower($_SERVER['USER']) != 'root') {
	die('Please run as super user'.chr(10));
}

define('ROOTPATH', realpath(__DIR__.'/../'));

echo chr(10).'Cleaning Cache Folder'.chr(10);

delete_files(ROOTPATH.'/var/cache');

function delete_files($searchDirectory) {
	foreach (glob(escapeshellcmd($searchDirectory).'/*') as $folderitem) {
		if (is_dir($folderitem)) {
			find_files($folderitem);
		} else {
			unlink($folderitem);
			echo $folderitem.chr(10);
		}
	}
}
