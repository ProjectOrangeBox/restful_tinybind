<?php
/* NOTE: this uses the FIRST match and stops */

$route = array (
  'cli/(.*)' => function($url) {
  	return 'cli/'.$url;
  },

  'admin/(.*)' => function($url) {
  	orange_middleware::on_request('AdminMiddleware','PublicMiddleware','GuiMiddleware','NavbarMiddleware');

  	return 'admin/'.$url;
  },

  'login(.*)' => function($url) {
  	orange_middleware::on_request('LoginMiddleware','PublicMiddleware','GuiMiddleware','NavbarMiddleware');

  	return 'login'.$url;
  },

  '(.*)' => function($url) {
  	orange_middleware::on_request('PublicMiddleware','GuiMiddleware','NavbarMiddleware');

  	return $url;
  },

  'default_controller' => 'main/index',
  '404_override' => 'main/route404',
);