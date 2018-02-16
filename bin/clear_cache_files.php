#!/usr/bin/env php
<?php

require 'support.inc.php';

heading('Cleaning Cache Folder');

delete_files(ROOTPATH.'/var/cache');
delete_files(ROOTPATH.'/var/uploads');
delete_files(ROOTPATH.'/var/downloads');

function delete_files($searchDirectory) {
	foreach (glob(escapeshellcmd($searchDirectory).'/*') as $folderitem) {
		if (is_dir($folderitem)) {
			delete_files($folderitem);
		} else {
			heading($folderitem);
			passthru('sudo rm -f '.s($folderitem));
		}
	}
}
