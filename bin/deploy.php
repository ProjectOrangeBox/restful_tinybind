#!/usr/bin/env php
<?php
/*
set 'foo bar' '123'
~in file flag
# php function
*another command group
cli command group
*/

define('ROOTPATH',realpath(__DIR__.'/../'));
define('ESCROOTPATH',str_replace(' ','\ ',ROOTPATH));
$_internal = [];

if (!file_exists(ROOTPATH.'/_env')) {
	error('Could not locate _env file');
}

heading('Using ENV File '.ROOTPATH.'/_env');

$_ENV = $_ENV + require ROOTPATH.'/_env';

$build_filename = ROOTPATH.'/build.json';

heading('Using Build File '.$build_filename);

if (file_exists($build_filename)) {
	$build_obj = json_decode(file_get_contents($build_filename));

	if ($build_obj === null) {
		error('build.json malformed');
	}
	
	$build_ary = (array)$build_obj;
} else {
	error('Can not locate build.json as "'.$build_filename.'"');
}

/* get option */
$args = $_SERVER['argv'];
array_shift($args);
$option = trim(implode(' ',$args));
$options = array_keys($build_ary);

if (!in_array($option,$options)) {
	if (empty($option)) {
		e('Please provide an option');
	} else {
		e('Option "'.$option.'" not found');
	}
	
	$html = [];
	
	foreach ($options as $h) {
		$x = strtolower(substr($h,0,1));
	
		if (ord($x) >= 97 && ord($x) <= 122) {
			$html[] = $h;
		}
	}
	
	sort($html);
	
	e(implode(', ',$html));
	exit();
}

if ($build_ary['~sudo'] === true) {
	passthru('sudo echo');
	define('SUDO','sudo ');
} else {
	define('SUDO','');
}

run($build_ary[$option]);

/* functions */

function run($commands) {
	global $build_ary;

	foreach ($commands as $cli) {
		merge($cli);
				
		if (substr($cli,0,4) == 'set ') {
			$cli = substr($cli,4);
			$args = str_getcsv($cli,' ',"'");
	
			$_internal[$args[0]] = $args[1];
		} elseif(substr($cli,0,1) == '*') {
			$option = substr($cli,1);
		
			$options = array_keys($build_ary);
			
			if (!in_array($option,$options)) {
				error('Option "'.$option.'" not found'.chr(10).implode(', ',$options));
			}
		
			run($build_ary[$option]);
		} elseif(substr($cli,0,1) == '#') {
			$cli = substr($cli,1);
			$args = str_getcsv($cli,' ',"'");
			
			$function = array_shift($args);
			
			call_user_func_array($function,$args);
		} else {
			e('<off>'.SUDO.$cli);

			passthru(SUDO.$cli,$exit_code);
			
			if ($exit_code > 0) {
				break;
			}
		}
	}
}

function e($txt) {
	echo color($txt).chr(10);
}

function heading($txt,$pad='-') {
	e('<cyan>'.str_pad('-'.$txt,exec('tput cols'),'-',STR_PAD_RIGHT).'</cyan>');
}

function error($txt) {
	e('<red>'.str_pad('*'.$txt,exec('tput cols'),'*',STR_PAD_RIGHT).'</red>');
	
	exit(6);
}

function table_heading() {
	global $column_widths;

	$input = func_get_args()[0];
	$text = '';

	foreach ($input as $txt=>$val) {
		$text .= str_pad($txt,$val,' ',STR_PAD_RIGHT).' ';
		
		$column_widths[] = $val;
	}
	
	e('<yellow>'.$text.'</yellow>');
}

function table_columns() {
	global $column_widths;

	$input = func_get_args();
	$text = '';

	foreach ($input as $idx=>$val) {
		$text .= str_pad($val,$column_widths[$idx],' ',STR_PAD_RIGHT).' ';
	}

	e($text);
}

function merge(&$input) {
	global $_internal;

	foreach ($_ENV as $key=>$val) {
		$input = str_replace('{'.strtolower($key).'}',$val,$input);
	}
	
	$input = str_replace(['{rootpath}','{erootpath}','{filename_date}'],[ROOTPATH,ESCROOTPATH,date('Y-m-d-H:ia')],$input);

	foreach ((array)$_internal as $key=>$val) {
		$input = str_replace('{'.strtolower($key).'}',$val,$input);
	}
}

function git_update($path,$branch=null) {
	$branch = ($branch) ? $branch : $_ENV['GITBRANCH'];

	if (!file_exists($path.'.git')) {
		e('<red>Not a git folder '.$path);
	} else {
		passthru('cd '.$path.';git fetch --all;git reset --hard origin/'.$branch);
	}
}

function s($input) {
	return str_replace(' ','\ ',$input);
}

function git_status($path) {
	exec('find '.$path.' -name FETCH_HEAD',$output);
	
	table_heading(['Package'=>32,'Branch'=>16,'Hash'=>42]);
	
	foreach ($output as $o) {
		$dirname = dirname(dirname($o));
	
		$branch = exec("cd ".s($dirname).";git rev-parse --abbrev-ref HEAD");
		$hash = exec("cd ".s($dirname).";git rev-parse --verify HEAD");
		
		$sections = explode('/',$dirname);
		$package = end($sections);
	
		table_columns($package,$branch,$hash);
	}
}

function color($input) {
	// Set up shell colors
	$foreground_colors['off'] = '0;0';
	
	$foreground_colors['black'] = '0;30';
	$foreground_colors['dark_gray'] = '1;30';
	$foreground_colors['blue'] = '0;34';
	$foreground_colors['light_blue'] = '1;34';
	$foreground_colors['green'] = '0;32';
	$foreground_colors['light_green'] = '1;32';
	$foreground_colors['cyan'] = '0;36';
	$foreground_colors['light_cyan'] = '1;36';
	$foreground_colors['red'] = '0;31';
	$foreground_colors['light_red'] = '1;31';
	$foreground_colors['purple'] = '0;35';
	$foreground_colors['light_purple'] = '1;35';
	$foreground_colors['brown'] = '0;33';
	$foreground_colors['yellow'] = '1;33';
	$foreground_colors['light_gray'] = '0;37';
	$foreground_colors['white'] = '1;37';
	
	foreach ($foreground_colors as $color=>$console) {
		$input = str_replace('<'.$color.'>',"\033[".$console."m",$input);
		$input = str_replace('</'.$color.'>',"\033[0m",$input);
	}
	
	return $input;
}
