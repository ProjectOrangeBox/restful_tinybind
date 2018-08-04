<?php
/* NOTE: this uses the FIRST match and stops */

$route = array (
  'cli/(.*)' => function($url,$router) {
  	return 'cli/'.$url;
  },

  'admin/(.*)' => function($url,$router) {
  	$router->on_request('AdminMiddleware','PublicMiddleware','GuiMiddleware','WhoopsMiddleware','NavbarMiddleware');

  	return 'admin/'.$url;
  },

  'login(.*)' => function($url,$router) {
  	$router->on_request('LoginMiddleware','PublicMiddleware','GuiMiddleware','WhoopsMiddleware','NavbarMiddleware');

  	return 'login'.$url;
  },

  '(.*)' => function($url,$router) {
  	$router->on_request('PublicMiddleware','GuiMiddleware','WhoopsMiddleware','NavbarMiddleware');

  	return $url;
  },

  'default_controller' => 'main/index',
  '404_override' => 'main/route404',
);
