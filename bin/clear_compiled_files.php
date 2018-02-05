#!/usr/bin/env php
<?php

passthru('sudo echo');

define('ROOTPATH', realpath(__DIR__.'/../'));

echo 'Cleaning Compile Folder'.chr(10);

delete_files(ROOTPATH.'/compiled');

function delete_files($searchDirectory) {
	foreach (glob(escapeshellcmd($searchDirectory).'/*') as $folderitem) {
		if (is_dir($folderitem)) {
			delete_files($folderitem);
		} else {
			passthru("sudo rm -f '".$folderitem."'");

			echo $folderitem.chr(10);
		}
	}
}
