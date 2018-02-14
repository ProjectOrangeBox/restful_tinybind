#!/usr/bin/env php
<?php

passthru('sudo echo');

define('ROOTPATH',realpath(__DIR__.'/../'));

passthru(s(ROOTPATH).'/bin/clear_cache_files.php');
passthru(s(ROOTPATH).'/bin/fix_permissions.php');
passthru(s(ROOTPATH).'/bin/fix_symlinks.php');

function s($input) {
	return str_replace(' ','\ ',$input);
}
