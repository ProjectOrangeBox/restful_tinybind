#!/usr/bin/env php
<?php

require 'support.inc.php';

env_required('GITBRANCH');

echo 'Using branch '.$_ENV['GITBRANCH'].chr(10);

exec('find '.s(ROOTPATH).' -name .git',$repros);

foreach ($repros as $repro) {
	$repro = dirname($repro);

	header($repro);

	passthru('cd '.s($repro).';git fetch --all');
	passthru('cd '.s($repro).';git reset --hard origin/'.$_ENV['GITBRANCH']);
}
