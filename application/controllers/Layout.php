<?php

class Layout extends CI_Controller {

	/* default */
	public function index()
	{
		$this->load->view('index.html');
	}

	/* get bind templates */
	public function get()
	{
		$this->load->view('/bind_templates/'.preg_replace("/[^\/a-zA-Z0-9]+/", "", implode('/',func_get_args())).'.bind.php');
	}

} /* end class */
