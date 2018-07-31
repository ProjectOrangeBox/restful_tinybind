<?php
/* NOTE: this uses the FIRST match and stops */

$route = array (
  'default_controller' => 'main/index',

  'cli/(.*)' => function($url) {
  	return 'cli/'.$url;
  },

  'admin/(.*)' => function($url) {
  	orange_middleware::on_request('AdminMiddleware','PublicMiddleware','GuiMiddleware','WhoopsMiddleware');

  	return 'admin/'.$url;
  },

  'login(.*)' => function($url) {
  	orange_middleware::on_request('LoginMiddleware','PublicMiddleware','GuiMiddleware','WhoopsMiddleware');

  	return 'login'.$url;
  },

  '(.*)' => function($url) {
  	orange_middleware::on_request('PublicMiddleware','GuiMiddleware','WhoopsMiddleware');

  	return $url;
  },

  '404_override' => 'main/route404',
  'translate_uri_dashes' => false,
);
