<?php

class Test extends CI_Controller {

	public function index() {
		$this->Restful_model
			->page('nav',$this->Nav_model->get(2))
			->send(200);
	}

} /* end class */
