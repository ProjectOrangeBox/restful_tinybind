<?php

class Test extends CI_Controller
{

	public function index()
	{
		$this->Restful_model
			->send(200);
	}
} /* end class */
