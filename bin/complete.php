#!/usr/bin/env php
<?php

passthru('sudo echo');

define('ROOTPATH', str_replace(' ','\\ ',realpath(__DIR__.'/../')));

passthru(ROOTPATH.'/bin/clear_cache_files.php');
passthru(ROOTPATH.'/bin/fix_permissions.php');
passthru(ROOTPATH.'/bin/fix_symlinks.php');
