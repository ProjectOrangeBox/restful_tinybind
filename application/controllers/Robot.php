<?php

class Robot extends MY_Controller
{
	public $controller_path = '/robot';
	public $controller_title = 'Robot';
	public $controller_titles = 'Robots';
	public $controller_model = 'Robots_model';

	protected function send(int $success, int $fail): void
	{
		/* add our select (drop down rows) to the model */
		$this->Restful_model->form('select', $this->RobotCatalogs_model->all());

		parent::send($success, $fail);
	}
} /* end class */
