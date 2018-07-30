<?php

$route = array (
  'default_controller' => 'main/index',

  'cli/(.*)' => function($url) {
  	return 'cli/'.$url;
  },

  'admin/(.*)' => function($url) {
  	orange_middleware::on_request('AdminMiddleware','PublicMiddleware','GuiMiddleware');

  	orange_middleware::on_responds('AdminMiddleware');

  	return 'admin/'.$url;
  },

  'login(.*)' => function($url) {
  	orange_middleware::on_request('LoginMiddleware','PublicMiddleware','GuiMiddleware');

  	return 'login'.$url;
  },

  '(.*)' => function($url) {
  	orange_middleware::on_request('PublicMiddleware','GuiMiddleware');

  	return $url;
  },

  '404_override' => 'main/route404',
  'translate_uri_dashes' => false,
);
