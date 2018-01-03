<?php

class BlogsController extends MY_Controller {
	use admin_controller_trait;

	public $controller        = 'blogs';
	public $controller_path   = '/blogs';
	public $controller_model  = 'blogs_model';

} /* end BlogsController */
