#!/usr/bin/env php
<?php

require 'support.inc.php';

$options = implode('|',$_SERVER['argv']);

$reverse = (strpos($options,'-r') !== false);
$copy = (strpos($options,'-c') !== false);

if ($copy) {
	heading('Copying Folders');
} else {
	heading('Symlink Folders');
}

if (!$reverse) {
	heading('From Package to Public');
} else {
	heading('From Public to Package');
}

if (isset($composer_obj->orange->symlink)) {

	$links = $composer_obj->orange->symlink;

	if (is_array($links)) {
		foreach ($links[0] as $public => $private) {
			if (substr($public,0,1) !== '#') {
				if ($reverse) {
    			list($public,$private) = array($private,$public);
				}

				heading(ROOTPATH.$private.' >> '.ROOTPATH.$public);
				
				if ($copy) {
					passthru('sudo cp -R '.s(ROOTPATH.$private).' '.s(ROOTPATH.$public));
				} else {
					relative_symlink($private, $public);
				}
			}
		}
	}
}
