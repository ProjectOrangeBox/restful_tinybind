<?php

$config['script_attributes'] = ['src' => '', 'type' => 'text/javascript', 'charset' => 'utf-8'];

$config['link_attributes'] = ['href' => '', 'type' => 'text/css', 'rel' => 'stylesheet'];

$config['domready_javascript'] = 'document.addEventListener("DOMContentLoaded",function(e){%%});';

$config['page_prefix'] = 'page_';

/*
Send in page values for ALL pages arrays of values also supported

meta
	'meta'=>['attr'=>'','name'=>'','content'=>'']

script
	'script'=>'alert("Welcome!");'

domready
	'domready'=>'alert("Page Loaded");'

title
	'title'=>''

style
	'style'=>'* {font-family: roboto}'

js
	'js'=>'/assets/javascript.js'

css
	'css'=>'/assets/application.css'

body_class
	'body_class'=>'app'

*/

$config['page_'] = [
	'title'=>'SkyNet',
];

$config['page_min'] = true;
