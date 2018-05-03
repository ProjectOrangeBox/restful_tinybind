<?php

class Addon_tasksController extends MY_Controller {
	use admin_controller_trait;

	public $controller        	= 'Addon_tasks';
	public $controller_path   	= '/addon_tasks';
	public $controller_model		= 'addon_tasks_model';
	public $controller_title		= 'Addon Task';
	public $controller_titles		= 'Addon Tasks';
	public $controller_order_by = 'id';

} /* end Addon_tasksController */
