<?php
/**
 * NOTE: this uses the FIRST match and stops
 */

$route = array(
	'vendor_stock/(.*)' => function ($url, $router) {
		$router->on_request('PublicMiddleware', 'GuiMiddleware');
		$router->on_responds('NavbarMiddleware');

		return 'vendor_stock/vendor/'.$url;
	},

  'cli/(.*)' => function ($url, $router) {
	  return 'cli/'.$url;
  },

  'admin/(.*)' => function ($url, $router) {
	  $router->on_request('AdminMiddleware', 'PublicMiddleware', 'GuiMiddleware');
		$router->on_responds('NavbarMiddleware');

	  return 'admin/'.$url;
  },

  'login(.*)' => function ($url, $router) {
	  $router->on_request('LoginMiddleware', 'PublicMiddleware', 'GuiMiddleware');
		$router->on_responds('NavbarMiddleware');

	  return 'login'.$url;
  },

  '(.*)' => function ($url, $router) {
	  $router->on_request('PublicMiddleware', 'GuiMiddleware');
		$router->on_responds('NavbarMiddleware');

	  return $url;
  },

  'default_controller' => 'main/index',
  '404_override' => 'main/route404',
);
