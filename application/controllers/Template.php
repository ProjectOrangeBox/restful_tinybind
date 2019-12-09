<?php

class Template extends CI_Controller
{
	protected $cacheFor = 6000;

	/* Request a Block Template */
	public function _remap(): void
	{
		$segment = $this->uri->rsegment_array();

		/* remove the controller (template) */
		array_shift($segment);

		$template = '/' . preg_replace("/[^\/a-zA-Z0-9]+/", '', implode('/', $segment)) . '.html';

		/* simple clean up */
		$this->restful
			->template($this->load->view($template, [], true), $this->cacheFor)
			->send(200);
	}
} /* end class */
