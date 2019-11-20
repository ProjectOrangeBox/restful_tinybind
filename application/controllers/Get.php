<?php

class Get extends CI_Controller {
	protected $superStorageCacheSeconds = 600;

	/* default empty */
	public function index() : void
	{
		$this->load->view('index.html');
	}

	/* get bind templates */
	public function layout() : void
	{
		/* simple clean up */
		$file = preg_replace("/[^\/a-zA-Z0-9]+/", "", implode('/',func_get_args()));

		/* file location */
		$template = '/'.$file.'.html';

		$this->Restful_model
			->template($this->load->view($template,[],true),$this->superStorageCacheSeconds)
			->send(200);
	}

	public function configuration() : void
	{
		$this->Restful_model
			->config('clearCache',false)
			->config('olderThanCache',$this->superStorageCacheSeconds)
			->config('templateCache',$this->superStorageCacheSeconds)
			->config('storageCache',$this->superStorageCacheSeconds)
			->page('nav',$this->Nav_model->get(2))
			->send(200);
	}

} /* end class */