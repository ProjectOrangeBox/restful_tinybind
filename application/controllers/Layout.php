<?php

class Layout extends CI_Controller {

	/* default */
	public function index()
	{
		$this->load->view('index');
	}

	/* get bind templates */
	public function get()
	{
		$this->load->view('/bind_templates/'.implode('/',func_get_args()).'.bind.php');
	}

} /* end class */
