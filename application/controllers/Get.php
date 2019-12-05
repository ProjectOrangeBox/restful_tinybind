<?php

class Get extends CI_Controller
{
	protected $superStorageCacheSeconds = 6000;
	protected $cache = false;

	/* default empty */
	public function index(): void
	{
		$this->load->view('index');
	}

	/* get binable template */
	public function layout(): void
	{
		/* simple clean up */
		$file = preg_replace("/[^\/a-zA-Z0-9]+/", '', implode('/', func_get_args()));

		/* file location */
		$template = '/' . $file . '.html';

		$this->Restful_model
			->template($this->load->view($template, [], true), $this->superStorageCacheSeconds)
			->send(200);
	}

	/*
	get the nav model data
	*/
	public function navModel(): void
	{
		$this->Restful_model
			->model($this->nav_model->get(2))
			->send(200);
	}

	/*
	return block configuration values

	these are merged directly with the blocks config object properties
	*/
	public function configuration(): void
	{
		$this->Restful_model
			->config('clearCache', !$this->cache)
			->config('olderThanCache', $this->superStorageCacheSeconds)
			->config('templateCache', $this->superStorageCacheSeconds)
			->config('storageCache', $this->superStorageCacheSeconds)
			->send(200);
	}
} /* end class */
