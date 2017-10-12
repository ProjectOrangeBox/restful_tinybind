<?php

class ThemeMiddleware extends Middleware_base {

	public function run() {
		$this->load->library(['html']);
	}

} /* end class */
