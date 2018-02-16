#!/usr/bin/env php
<?php

require 'support.inc.php';

heading('Cleaning Session Folder');

delete_files(ROOTPATH.'/var/sessions');

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

function s($input) {
	return str_replace(' ','\ ',$input);
}
