<?php

class MainController extends MY_Controller {

	public function indexAction() {
		redirect('/login');
	}

	public function gitAction() {
		$table_template = [
			'table_open' => '<table class="table table-condensed table-bordered">',
		];

		ci('page')->data(['html'=>ci('orange_tools')->git_status('html',$table_template)])->render();
	}

} /* end class */