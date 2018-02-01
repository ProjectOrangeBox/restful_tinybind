#!/usr/bin/env php
<?php

define('ROOTPATH', realpath(__DIR__.'/../'));

/* search all the folder under root for .git/HEAD */
exec('find "'.ROOTPATH.'" -name HEAD',$output);

row('Package','Branch','Hash');

foreach ($output as $o) {
	$dirname = dirname($o);

	if (strpos($o,'/.git/HEAD') !== false) {
		$stringfromfile = file($o);

		$firstLine = $stringfromfile[0]; //get the string from the array

		$explodedstring = explode("/", $firstLine, 3); //separate out by the "/" in the string

		$branchname = $explodedstring[2]; //get the one that is always the branch name

		$key = str_replace('/.git/HEAD','',$o);

		$sections = explode('/',$key);

		$key = end($sections);

		$lines = file($dirname.'/FETCH_HEAD');

		$firstline = $lines[0];

		$segs  = explode(' ',str_replace([' ',chr(9)],' ',$firstline));

		row(trim($key),trim($branchname),trim($segs[0]));
	}
}

function row($package,$branch,$hash) {
	echo str_pad($package,32,' ',STR_PAD_RIGHT).str_pad($branch,16,' ',STR_PAD_RIGHT),str_pad($hash,36,' ',STR_PAD_RIGHT).chr(10);
}