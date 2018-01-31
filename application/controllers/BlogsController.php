<?php

class BlogsController extends MY_Controller {
	use admin_controller_trait;

	public $controller        	= 'blogs';
	public $controller_path   	= '/blogs';
	public $controller_model		= 'blogs_model';
	public $controller_title		= 'Blog';
	public $controller_titles		= 'Blogs';
	public $controller_order_by = 'blog_id';

} /* end BlogsController */
