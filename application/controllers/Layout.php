<?php

class Layout extends CI_Controller {
	protected $cachePagesForSeconds = 0;

	/* default */
	public function index() : void
	{
		$this->load->view('index.html');
	}

	/* get bind templates */
	public function get() : void
	{
		$this->Restful_model
			->template($this->load->view('/bind_templates/'.preg_replace("/[^\/a-zA-Z0-9]+/", "", implode('/',func_get_args())).'.html',[],true),$this->cachePagesForSeconds)
			->send(200);
	}

	public function configuration() : void
	{
		$this->Restful_model
			->flag('cache',$this->cachePagesForSeconds)
			->send(200);
	}

} /* end class */
