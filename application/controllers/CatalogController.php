<?php

class CatalogController extends \MY_Controller
{
	use rest_trait;

	public $controller					= 'Catalog';
	public $controller_path			= '/catalog';
	public $controller_model		= 'RobotsCatalog_model';
	public $controller_title		= 'Catalog';
	public $controller_titles		= 'Catalogs';

	/**
	 * seedAction
	 *
	 * @return void
	 */
	public function seedAction($number=100) : void
	{
		for ($i = 1; $i <= $number; $i++) {
			ci('Robots_model')->seed();
		}
	}

} /* end class */
