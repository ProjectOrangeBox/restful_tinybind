<?php

$config['script_attributes'] = ['src' => '', 'type' => 'text/javascript', 'charset' => 'utf-8'];

$config['link_attributes'] = ['href' => '', 'type' => 'text/css', 'rel' => 'stylesheet'];

$config['domready_javascript'] = 'document.addEventListener("DOMContentLoaded",function(e){%%});';

$config['page_prefix'] = 'page_';

$config['page_'] = [
	'title'=>'SkyNet',
	'css'=>'/theme/orange/assets/css/application.min.css',
	'js'=>[
		'/theme/orange/assets/js/application.min.js',
		'/theme/orange/assets/js/tools.min.js',
	]
];

$config['page_min'] = (env('SERVER_DEBUG') == 'development');
