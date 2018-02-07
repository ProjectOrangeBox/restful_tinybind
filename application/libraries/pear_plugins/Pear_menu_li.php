<?php

pear::attach('menu_li',function($permission,$url,$text) {
	if (user::can($permission)) {
		echo '<li><a href="'.site_url($url).'">'.$text.'</a></li>';
	}
});
