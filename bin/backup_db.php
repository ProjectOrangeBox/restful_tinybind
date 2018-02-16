#!/usr/bin/env php
<?php

define('ROOTPATH',realpath(__DIR__.'/../'));

if (!isset($_SERVER['argv'][1])) {
	die('please provide folder to save backups to'.chr(10));
}

$dir = rtrim($_SERVER['argv'][1],'/');

if (!file_exists($dir)) {
	die('can not locate "'.$dir.'"'.chr(10));
}

if (!file_exists(ROOTPATH.'/_env')) {
	die('can not locate "'.ROOTPATH.'/_env"'.chr(10));
}

$_ENV = $_ENV + require ROOTPATH.'/_env';

/* test */
$required = ['DBBACKUPDATABASE','DBBACKUPUSER','DBBACKUPPASSWORD'];

foreach ($required as $r) {
	if (!isset($_ENV[$r])) {
		die('missing $_ENV config value "'.$r.'"'.chr(10));
	}	
}

$file = $dir.'/backups/'.date('Y-m-d H:ia').'.'.$_ENV['DBBACKUPDATABASE'].'.sql.gz';

$mysqldump = '/usr/bin/mysqldump --extended-insert=FALSE --add-drop-table --add-drop-trigger --create-options --password=%password% --events --routines --single-transaction --triggers --user=%user% --databases %database%';

$mysqldump = str_replace(['%user%','%password%','%database%'],[$_ENV['DBBACKUPUSER'],$_ENV['DBBACKUPPASSWORD'],$_ENV['DBBACKUPDATABASE']],$mysqldump);

passthru($mysqldump.' | gzip -9 > '.str_replace(' ','\ ',$file));
