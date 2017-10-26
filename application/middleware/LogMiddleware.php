<?php

class LogMiddleware extends Middleware_base {

	public function run() {
		$file = '/Users/donmyers/Desktop/';
	
		$array = array($_SERVER,ci()->input->request());
	
		file_put_contents($file.'request.log',print_r($array,true),FILE_APPEND | LOCK_EX);
	}

} /* end class */
