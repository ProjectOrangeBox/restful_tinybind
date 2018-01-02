<?php

class Pear_menu_li {

	public function __construct() {
		pear::attach('menu_li',function($permission,$url,$text) {
			if (user::can($permission)) {
				echo '<li><a href="'.site_url($url).'">'.$text.'</a></li>';
			}
		});
	}

} /* end class */
