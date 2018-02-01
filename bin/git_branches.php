#!/usr/bin/env php
<?php

define('ROOTPATH', realpath(__DIR__.'/../'));

/* search all the folder under root for .git/HEAD */
exec('find "'.ROOTPATH.'" -name FETCH_HEAD',$output);

row('Package','Branch','Hash');

foreach ($output as $o) {
	$dirname = dirname(dirname($o));

	$branch = exec("cd '".$dirname."';git rev-parse --abbrev-ref HEAD");
	$hash = exec("cd '".$dirname."';git rev-parse --verify HEAD");
	
	$sections = explode('/',$dirname);
	$package = end($sections);

	row($package,$branch,$hash);
}

function row($package,$branch,$hash) {
	echo str_pad($package,32,' ',STR_PAD_RIGHT).str_pad($branch,16,' ',STR_PAD_RIGHT),str_pad($hash,36,' ',STR_PAD_RIGHT).chr(10);
}