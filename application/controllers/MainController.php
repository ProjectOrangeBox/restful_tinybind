<?php

class MainController extends MY_Controller {

	public function indexAction() {
		redirect('/login');
	
		$this->page->render();
	}

	public function gitAction() {
		$this->load->library('orange_tools');

		$this->page->data(['html'=>$this->orange_tools->git_status()])->render();
	}
	
	public function migrationCliAction($action='current') {
		$this->load->library('orange_tools');
		
		$this->orange_tools->migration($action);
	}
	
	public function fixCliAction() {
		$this->load->library('orange_tools');

		$this->orange_tools->fix();
	}

} /* end class */