<?php

/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Drivers
| 4. Helper files
| 5. Custom config files
| 6. Language files
| 7. Models
|
*/

/* if you try to load the array key instead load the array value */
$autoload['remap'] = [
	#'auth'=>'ldap_auth',
	#'o_user_entity'=>'O_ldap_user_entity',
];

/*
| -------------------------------------------------------------------
|  Auto-load Packages
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/
$autoload['packages'] = array(
	ROOTPATH.'/packages/quadratec/backorder',
	ROOTPATH.'/packages/quadratec/stock-status-check',
	ROOTPATH.'/packages/quadratec/drop-ships',
	ROOTPATH.'/packages/quadratec/affirm',
	ROOTPATH.'/packages/quadratec/status-board',
	ROOTPATH.'/packages/quadratec/gift-certificate',
	ROOTPATH.'/packages/projectorangebox/extra-validations',
	ROOTPATH.'/packages/misc/forgot',
	ROOTPATH.'/packages/misc/remember',
	ROOTPATH.'/packages/misc/register',
	ROOTPATH.'/packages/misc/opcache',
	ROOTPATH.'/packages/misc/config-viewer',
	ROOTPATH.'/packages/misc/librarian',
	ROOTPATH.'/packages/projectorangebox/migrations',
	ROOTPATH.'/packages/misc/cache-viewer',
	ROOTPATH.'/packages/misc/login-success',
	ROOTPATH.'/packages/misc/tooltips',
	ROOTPATH.'/packages/misc/handlebars',
	ROOTPATH.'/packages/misc/general_addons',
	ROOTPATH.'/packages/misc/scaffolding',
	ROOTPATH.'/packages/misc/tasks',
	ROOTPATH.'/packages/projectorangebox/orange',
	ROOTPATH.'/packages/projectorangebox/theme-orange',
	ROOTPATH.'/packages/misc/user_msgs',
	ROOTPATH.'/packages/kristine_rabbits',
	ROOTPATH.'/packages/rabbit_owners',
);

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in system/libraries/ or your
| application/libraries/ directory, with the addition of the
| 'database' library, which is somewhat of a special case.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'email', 'session');
|
| You can also supply an alternative library name to be assigned
| in the controller:
|
|	$autoload['libraries'] = array('user_agent' => 'ua');
*/
$autoload['libraries'] = array(
	'session',
	'errors',
	'database',
	'event',
	'validate',
	'wallet',
	'auth',
);

/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
| These classes are located in system/libraries/ or in your
| application/libraries/ directory, but are also placed inside their
| own subdirectory and they extend the CI_Driver_Library class. They
| offer multiple interchangeable driver options.
|
| Prototype:
|
|	$autoload['drivers'] = array('cache');
|
| You can also supply an alternative property name to be assigned in
| the controller:
|
|	$autoload['drivers'] = array('cache' => 'cch');
|
*/
$autoload['drivers'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/
$autoload['helper'] = array('url');

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/
$autoload['config'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/
$autoload['language'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('first_model', 'second_model');
|
| You can also supply an alternative model name to be assigned
| in the controller:
|
|	$autoload['model'] = array('first_model' => 'first');
*/
$autoload['model'] = [];
