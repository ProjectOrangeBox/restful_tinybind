<?php

class MainController extends MY_Controller {

	public function indexAction() {
		$this->page->render();
	}

	public function testAction($a=null,$b=null,$c=null) {
		$this->validate
			->variable('filter_slug',$a)
			->variable('filter_slug',$b)
			->variable('filter_slug|filter_md5',$c)
			->die_on_fail();
		
		
		var_dump($a,$b,$c);
	}
	
	public function migrationCliAction($action='current') {
		$this->load->library('orange_tools');
		
		$this->orange_tools->migration($action);
	}
	
	public function symlinkCliAction() {
		$this->load->library('orange_tools');

		$this->orange_tools->symlink();
	}

	public function permissionsCliAction() {
		$this->load->library('orange_tools');

		$this->orange_tools->fix_permissions();
	}

	public function fixCliAction() {
		$this->load->library('orange_tools');

		$this->orange_tools->fix();
	}
		
} /* end class */