<?php

class ThemeMiddleware extends Middleware_base {

	public function run() {
		$this->load->library(['pear']);
	}

} /* end class */
