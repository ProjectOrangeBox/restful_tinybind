<?php

class ThemeMiddleware extends Middleware_base {

	public function __construct() {
		$this->load->library(['pear']);
	}

} /* end class */
