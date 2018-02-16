#!/usr/bin/env php
<?php

require 'support.inc.php';

env_required('GITBRANCH');

columns_widths(32,16,36);

if (get_arg1(false)) {
	env_required('GITBRANCH');

	heading('Using branch '.$_ENV['GITBRANCH']);
	
	exec('find '.ESCROOTPATH.' -name .git',$repros);
	
	foreach ($repros as $repro) {
		$repro = dirname($repro);
	
		heading($repro);
	
		passthru('cd '.s($repro).';git fetch --all');
		passthru('cd '.s($repro).';git reset --hard origin/'.$_ENV['GITBRANCH']);
	}
} else {
	exec('find '.ESCROOTPATH.' -name FETCH_HEAD',$output);
	
	columns('Package','Branch','Hash');
	
	foreach ($output as $o) {
		$dirname = dirname(dirname($o));
	
		$branch = exec("cd ".s($dirname).";git rev-parse --abbrev-ref HEAD");
		$hash = exec("cd ".s($dirname).";git rev-parse --verify HEAD");
		
		$sections = explode('/',$dirname);
		$package = end($sections);
	
		columns($package,$branch,$hash);
	}

}