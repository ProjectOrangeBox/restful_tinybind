<?php

class AuthMiddleware extends Middleware_base {

	public function run() {
		/* are they logged in already? then try to take them to the dashboard */
		
		if (!user::is_guest()) {
			redirect(site_url('/{dashboard}'));
		}
	}

} /* end class */
