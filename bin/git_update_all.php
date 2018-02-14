#!/usr/bin/env php
<?php

passthru('sudo echo');

define('ROOTPATH',realpath(__DIR__.'/../'));

$_ENV = $_ENV + require ROOTPATH.'/_env';

if (!isset($_ENV['GITBRANCH'])) {
	die('GIT Branch Missing'.chr(10));
}

echo 'Using branch '.$_ENV['GITBRANCH'].chr(10);

exec('find '.s(ROOTPATH).' -name .git',$repros);

foreach ($repros as $repro) {
	$repro = dirname($repro);

	echo $repro.chr(10);

	passthru('cd '.s($repro).';git fetch --all');
	passthru('cd '.s($repro).';git reset --hard origin/'.$_ENV['GITBRANCH']);
}

function s($input) {
	return str_replace(' ','\ ',$input);
}
