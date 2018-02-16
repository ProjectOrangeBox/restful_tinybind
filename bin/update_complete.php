#!/usr/bin/env php
<?php

$dir = str_replace(' ','\ ',realpath(__DIR__.'/../'));

$code = 0;

$commands = [
	$dir.'/bin/backup_db.php '.$dir.'/support/backups',
	$dir.'/bin/take_site.php down',
	$dir.'/bin/git.php update',
	$dir.'/bin/repair.php',
	$dir.'/bin/clean.php caches',
	$dir.'/bin/link.php',
	$dir.'/bin/take_site.php up',
	$dir.'/bin/git.php',
];

foreach ($commands as $cli) {
	passthru($cli,$exit_code);
	
	if ($exit_code > 0) {
		break;
	}
}