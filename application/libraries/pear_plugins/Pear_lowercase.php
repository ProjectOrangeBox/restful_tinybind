<?php

class Pear_lowercase {

	public function __construct() {
		pear::attach('lowercase_open',function() {
			ob_start();
		});

		pear::attach('lowercase_close',function() {
			$buffer = ob_get_contents();
	
			ob_end_clean();
				
			echo strtolower($buffer);
		});
	}

} /* end class */
