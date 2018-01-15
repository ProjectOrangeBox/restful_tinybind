<?php
$route = array (
  'default_controller' => 'main/index',

  'admin/(.*)' => function($url) {
  	middleware('AdminMiddleware','PublicMiddleware','GuiMiddleware');

  	return 'admin/'.$url;
  },
  '(.*)' => function($url) {
  	middleware('PublicMiddleware','GuiMiddleware');

  	return $url;
  },

  '404_override' => 'main/route404',
  'translate_uri_dashes' => false,
);
