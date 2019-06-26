<?php

class Food extends MY_Controller {
	public $controller_path = '/food';
	public $controller_title = 'Food';
	public $controller_titles = 'Foods';
	public $controller_model = 'Food_model';

	public function indexGet() : void
	{
		$this->load->view('/food/index.html',$this->data);
	}

	public function editGet($id=null) : void
	{
		$this->load->view('/food/detail.html',$this->data);
	}

	public function createGet() : void
	{
		$this->load->view('/food/detail.html',$this->data);
	}

} /* end class */
