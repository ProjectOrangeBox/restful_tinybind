<?php

class Plugin_lowercase {

	public function __construct() {
		html::attach('lowercase_open',function() {
			ob_start();
		});

		html::attach('lowercase_close',function() {
			$buffer = ob_get_contents();
	
			ob_end_clean();
				
			echo strtolower($buffer);
		});
	}

} /* end class */
