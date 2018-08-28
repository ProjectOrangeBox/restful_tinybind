<?php
/* NOTE: this uses the FIRST match and stops */

$route = array (
  '(.*)' => function($url,$router) {
  	return $url;
  },

  'default_controller' => 'main/index',
);
