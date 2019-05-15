<?php

class RestController extends \MY_Controller
{
	use rest_trait;

	public $controller					= 'Rest';
	public $controller_path			= '/rest';
	public $controller_model		= 'Robots_model';
	public $controller_title		= 'Robot';
	public $controller_titles		= 'Robots';

	protected function prepRespondsModel() : Restful_model
	{
		$this->respondsModel = new Restful_model;

		return $this->respondsModel
			->form('select',ci('robotsCatalog_model')->get_many())
			->page([
				'path' => $this->controller_path,
				'title' => $this->controller_title,
				'titles' => $this->controller_titles,
			]);
	}


} /* end class */
