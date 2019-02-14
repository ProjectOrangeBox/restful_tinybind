<?php

$config['upload_path'] = ROOTPATH.'/var/uploads/';
$config['allowed_types'] = ['gif','jpg','png'];
$config['max_size'] = '9000';

$config['max_width'] = '2000';
$config['max_height'] = '2000';

$config['move_to'] = WWW.'/images/';
$config['strip folder'] = WWW;

/**
 *
 * This array matches the name of a input field
 * this is merged with the base configuration array will additional
 * configuration for this input field
 *
 */
$config['filea'] = [
	'allowed_types'=>['xls','xlsx'],
	'move_to' => ROOTPATH.'/var/tmp/',
	'strip folder' => ROOTPATH,
];


$config['imageb'] = [
	'allowed_types'=>['xls','xlsx'],
	'move_to' => ROOTPATH.'/var/tmp/',
	//'encrypt_name'=>true,
	'strip folder' => ROOTPATH,
];
