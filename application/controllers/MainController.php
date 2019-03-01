<?php

class MainController extends \MY_Controller
{
	use admin_index_render_controller_trait;

	public function route404Action()
	{
		show_404();
	}
} /* end class */
