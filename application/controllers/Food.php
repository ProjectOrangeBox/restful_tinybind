<?php

class Food extends MY_Controller {
	public $controller_path = '/food';
	public $controller_title = 'Food';
	public $controller_titles = 'Foods';
	public $controller_model = 'Food_model';

	public function indexHtml() : void
	{
		$this->load->view('/food/index.html');
	}

	public function detailHtml($id=null) : void
	{
		$this->load->view('/food/detail.html');
	}

} /* end class */
