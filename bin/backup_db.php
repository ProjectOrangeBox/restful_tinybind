#!/usr/bin/env php
<?php

require 'support.inc.php';

$dir = rtrim(get_arg1('Please provide folder to save backups to.'),'/');

if (!file_exists($dir)) {
	error('can not locate "'.$dir.'"');
}

env_required(['DBBACKUPDATABASE','DBBACKUPUSER','DBBACKUPPASSWORD']);

$file = $dir.'/'.date('Y-m-d H:ia').'.'.$_ENV['DBBACKUPDATABASE'].'.sql.gz';

$mysqldump = '/usr/bin/mysqldump --extended-insert=FALSE --add-drop-table --add-drop-trigger --create-options --password=%password% --events --routines --single-transaction --triggers --user=%user% --databases %database%';

$mysqldump = str_replace(['%user%','%password%','%database%'],[$_ENV['DBBACKUPUSER'],$_ENV['DBBACKUPPASSWORD'],$_ENV['DBBACKUPDATABASE']],$mysqldump);

shell($mysqldump.' | gzip -9 > '.str_replace(' ','\ ',$file));
shell('sudo find '.ESCROOTPATH.'/backups -mtime +7 -type f -delete');
