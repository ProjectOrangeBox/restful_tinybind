<?php

class ServerConfiguration extends CI_Controller
{
	protected $flushOlderThan = 6000;
	protected $cache = false;

	/**
	 * Block Configuration Values
	 *
	 * These are merged directly with the blocks config object properties
	 */
	public function index(): void
	{
		$this->restful
			->config('clearCache', !$this->cache) /* clear ALL cached records */
			->config('olderThanCache', $this->flushOlderThan) /* in production clear records older than (this way if you need to flush out old records you can without new records being deleted */
			->send(200);
	}
} /* end class */
