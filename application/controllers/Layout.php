<?php

class Layout extends CI_Controller {
	protected $superStorageCacheSeconds = 600;

	/* default */
	public function index() : void
	{
		$this->load->view('index.html');
	}

	/* get bind templates */
	public function get() : void
	{
		$this->Restful_model
			->template($this->load->view('/bind_templates/'.preg_replace("/[^\/a-zA-Z0-9]+/", "", implode('/',func_get_args())).'.html',[],true),$this->superStorageCacheSeconds)
			->send(200);
	}

	public function configuration() : void
	{
		$this->Restful_model
			->config('clearCache',false)
			->config('olderThanCache',$this->superStorageCacheSeconds)
			->config('templateCache',$this->superStorageCacheSeconds)
			->config('storageCache',$this->superStorageCacheSeconds)
			->send(200);
	}

} /* end class */
