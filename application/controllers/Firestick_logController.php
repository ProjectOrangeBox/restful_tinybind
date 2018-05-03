<?php

class Firestick_logController extends MY_Controller {
	use admin_controller_trait;

	public $controller        	= 'Firestick_log';
	public $controller_path   	= '/{tablename}';
	public $controller_model		= '{tablename}_model';
	public $controller_title		= 'Firestick Log';
	public $controller_titles		= 'Firestick Logs';
	public $controller_order_by = 'ip';

} /* end Firestick_logController */
