<?php
$route = array (
  'default_controller' => 'main/index',

  'backorder(.*)' => function($url) {
  	middleware('AdminMiddleware','PublicMiddleware','GuiMiddleware','TooltipMiddleware');

  	return 'backorder'.$url;
  },
  'admin/(.*)' => function($url) {
  	middleware('AdminMiddleware','PublicMiddleware','GuiMiddleware','TooltipMiddleware');

  	return 'admin/'.$url;
  },
  '(.*)' => function($url) {
  	middleware('PublicMiddleware','GuiMiddleware');

  	return $url;
  },

  '404_override' => 'main/route404',
  'translate_uri_dashes' => false,
);
