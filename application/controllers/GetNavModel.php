<?php

class GetNavModel extends CI_Controller
{
	/* Get the nav model data */
	public function index(): void
	{
		/* return restful model */
		$this->restful
			->model($this->nav_model->get(2))
			->send(200);
	}
} /* end class */
