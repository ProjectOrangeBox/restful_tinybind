<?php 

define('ROOTPATH',realpath(__DIR__.'/../'));
define('ESCROOTPATH',str_replace(' ','\ ',ROOTPATH));

passthru('sudo echo');

if (!file_exists(ROOTPATH.'/_env')) {
	error('Could not locate _env file');
}

$_ENV = $_ENV + require ROOTPATH.'/_env';

$filename = ROOTPATH.'/composer.json';

e('Using Composer File '.$filename);

if (file_exists($filename)) {
	$composer_obj = json_decode(file_get_contents($filename));

	if ($composer_obj === null) {
		error('composer.json malformed');
	}
} else {
	error('can not locate composer.json as "'.$filename.'"');
}

/* functions */

function get_arg1($error='Please provide an option.') {
	if ($error !== false) {
		if (!isset($_SERVER['argv'][1])) {
			error($error);
		}
	}

	return isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : false;
}

function shell($cmd, &$stdout=null, &$stderr=null) {
	$cmd = str_replace('{rootpath}',ESCROOTPATH,$cmd);

	e('>> '.$cmd);
	
	$proc = proc_open($cmd,[
			1 => ['pipe','w'],
			2 => ['pipe','w'],
	],$pipes);

	$stdout = stream_get_contents($pipes[1]);
	fclose($pipes[1]);

	$stderr = stream_get_contents($pipes[2]);
	fclose($pipes[2]);

	return proc_close($proc);
}

function s($input) {
	return str_replace(' ','\ ',$input);
}

function env_required($required) {
	foreach ((array)$required as $r) {
		if (!isset($_ENV[$r])) {
			error('missing $_ENV config value "'.$r.'"');
		}	
	}
}

/* figure out relative path */
function relative_symlink($target, $link) {
	/* remove the link that might be there */

	/* let's make sure the rootpath is NOT there since we add it */
	if (substr($link, 0, strlen(ROOTPATH)) == ROOTPATH) {
		$link = substr($link, strlen(ROOTPATH));
	}

	if (substr($target, 0, strlen(ROOTPATH)) == ROOTPATH) {
		$target = substr($target, strlen(ROOTPATH));
	}

	/* remove it if it's already there */
	//@unlink(ROOTPATH . $link);
	passthru('sudo rm -fdr '.s(ROOTPATH.$link));

	/* create it */
	passthru('sudo ln -s '.s(ROOTPATH.$target).' '.s(ROOTPATH.$link));
}

function row($package,$branch,$hash) {
	echo str_pad($package,32,' ',STR_PAD_RIGHT).str_pad($branch,16,' ',STR_PAD_RIGHT),str_pad($hash,36,' ',STR_PAD_RIGHT).chr(10);
}

function down_html() {
$html = <<<HTML
<!doctype html>
<title>Site Maintenance</title>
<style>
	body { text-align: center; padding: 150px; }
	h1 { font-size: 50px; }
	body { font: 20px Helvetica, sans-serif; color: #333; }
	article { display: block; text-align: left; width: 650px; margin: 0 auto; }
</style>
<article>
		<h1>We&rsquo;ll be back soon!</h1>
		<div>
				<p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. We&rsquo;ll be back online shortly!</p>
				<p>&mdash; The Team</p>
		</div>
</article>
HTML;

	return $html;
}

function e($txt) {
	echo $txt.chr(10);
}

function heading($txt,$die=false) {
	echo str_pad('-'.$txt,exec('tput cols'),'-',STR_PAD_RIGHT).chr(10);
	
	if ($die) {
		exit(6);
	}
}

function error($txt) {
	echo str_pad('*'.$txt,exec('tput cols'),'-',STR_PAD_RIGHT).chr(10);
	
	exit(6);
}

function columns_widths() {
	global $column_widths;

	$column_widths = func_get_args();
}

function columns() {
	global $column_widths;

	$input = func_get_args();

	foreach ($input as $idx=>$val) {
		echo str_pad($val,$column_widths[$idx],' ',STR_PAD_RIGHT);
	}

	echo chr(10);
}
