#!/usr/bin/env php
<?php

require 'support.inc.php';

columns_widths(32,16,36);

/* search all the folder under root for .git/HEAD */
exec('find '.s(ROOTPATH).' -name FETCH_HEAD',$output);

columns('Package','Branch','Hash');

foreach ($output as $o) {
	$dirname = dirname(dirname($o));

	$branch = exec("cd ".s($dirname).";git rev-parse --abbrev-ref HEAD");
	$hash = exec("cd ".s($dirname).";git rev-parse --verify HEAD");
	
	$sections = explode('/',$dirname);
	$package = end($sections);

	columns($package,$branch,$hash);
}
