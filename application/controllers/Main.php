<?php

class Main extends MY_Controller {

	public function indexGet() : void
	{
		$this->load->view('/main/index.html');
	}

} /* end class */
