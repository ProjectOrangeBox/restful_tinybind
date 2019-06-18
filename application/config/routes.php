<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'get';

$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
$isJson = (!empty($_SERVER['HTTP_ACCEPT']) && strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/json') !== false);

if ($isAjax || $isJson) {
	/* handle REST http methods */
	$route['robot/create']['POST'] = 'robot/createPost';
	$route['robot/edit/(:num)']['PATCH'] = 'robot/editPatch/$1';
	$route['robot/delete/(:num)']['DELETE'] = 'robot/deleteDelete/$1';

	$route['catalog/create']['POST'] = 'catalog/createPost';
	$route['catalog/edit/(:num)']['PATCH'] = 'catalog/editPatch/$1';
	$route['catalog/delete/(:num)']['DELETE'] = 'catalog/deleteDelete/$1';

	$route['zipcodes/create']['POST'] = 'zipcodes/createPost';
	$route['zipcodes/edit/(:num)']['PATCH'] = 'zipcodes/editPatch/$1';
	$route['zipcodes/delete/(:num)']['DELETE'] = 'zipcodes/deleteDelete/$1';

	$route['people/create']['POST'] = 'people/createPost';
	$route['people/edit/(:num)']['PATCH'] = 'people/editPatch/$1';
	$route['people/delete/(:num)']['DELETE'] = 'people/deleteDelete/$1';

	$route['multi/create']['POST'] = 'multi/createPost';
	$route['multi/edit/(:num)']['PATCH'] = 'multi/editPatch/$1';
	$route['multi/delete/(:num)']['DELETE'] = 'multi/deleteDelete/$1';

	$route['(:any)'] = 'robot';
} else {
	$route['test'] = 'test/index';

	/* reguardless of the URL use the default */
	$route['(.*)'] = $route['default_controller'];
}

$route['404_override'] = '';

$route['translate_uri_dashes'] = TRUE;
