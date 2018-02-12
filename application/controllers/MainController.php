<?php

class MainController extends MY_Controller {

	public function indexAction() {
		ci('page')->render();
	}

	public function hijackAction($key=null) {
		$parts = explode(chr(0),hex2bin($key));

		/* must be 3 parts */
		if (count($parts) != 3) {
			ci('errors')->display(403);
		}

		/* greater than 10 minutes */
		if (date('U') - $parts[1] > 600) {
			ci('errors')->display(403);
		}

		/* check hmac */
		if ($parts[2] !== md5($parts[0].chr(0).$parts[1].chr(0).ci('config')->item('encryption_key'))) {
			ci('errors')->display(403);
		}

		ci('auth')->refresh_userdata((int)$parts[0]);

		redirect('{dashboard}');
	}

} /* end class */
