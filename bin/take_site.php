#!/usr/bin/env php
<?php

define('ROOTPATH',realpath(__DIR__.'/../'));

$dir = @$_SERVER['argv'][1];

if ($dir == 'up') {
	unlink(ROOTPATH.'/public/index.html');
} elseif($dir == 'down') {
	file_put_contents(ROOTPATH.'/public/index.html',down_html());
} else {
	die('please provide up or down'.chr(10));
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
