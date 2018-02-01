#!/usr/bin/env php
<?php

define('ROOTPATH', realpath(__DIR__.'/../'));

echo git_status();

function git_status($output_as='none',$table_template=null) {
	/* search all the folder under root for .git/HEAD */
	exec('find "'.ROOTPATH.'" -name HEAD',$output);

	$array = false;

	foreach ($output as $o) {
		$dirname = dirname($o);

		echo $dirname.chr(10);

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

			$array[$key] = ['branch'=>$branchname,'commit'=>$segs[0]];
		}
	}

	$table = [];

	if ($array) {
		$table[] = ['Package','Branch','Hash'];

		foreach ($array as $key=>$val) {
			$table[] = [$key,$val['branch'],$val['commit']];
		}

		$responds = '';

		foreach ($table as $row) {
			$responds .= trim(str_pad($row[0],32,' ',STR_PAD_RIGHT).str_pad($row[1],16,' ',STR_PAD_RIGHT),str_pad($row[2],36,' ',STR_PAD_RIGHT)).chr(10);
		}
	} else {
		$responds = 'No GIT directory\'s found';
	}

	return $responds;
}
