#!/usr/bin/env php
<?php

require 'support.inc.php';

$options = implode('|',$_SERVER['argv']);

$reverse = (strpos($options,'-r') !== false);
$copy = (strpos($options,'-c') !== false);

if ($copy) {
	echo 'Copying Folders'.chr(10);
} else {
	echo 'Symlink Folders'.chr(10);
}

if (!$reverse) {
	echo 'From Package to Public'.chr(10);
} else {
	echo 'From Public to Package'.chr(10);
}

if (isset($composer_obj->orange->symlink)) {

	$links = $composer_obj->orange->symlink;

	if (is_array($links)) {
		foreach ($links[0] as $public => $private) {
			if (substr($public,0,1) !== '#') {
				if ($reverse) {
    			list($public,$private) = array($private,$public);
				}

				echo ROOTPATH.$private.' >> '.ROOTPATH.$public.chr(10);
				
				if ($copy) {
					passthru('sudo cp -R '.s(ROOTPATH.$private).' '.s(ROOTPATH.$public));
				} else {
					relative_symlink($private, $public);
				}
			}
		}
	}
}
