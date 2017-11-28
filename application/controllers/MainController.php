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
	
	public function emailAction() {
		echo '<pre><h1>test</h1>';
	
		$this->load->library('email');
		
		$this->email->from('jenk@quadratec.com', 'Your Name');
		$this->email->to('donmyers@quadratec.com');
		
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');
		
		$this->email->send(FALSE);
		
		$x = $this->email->print_debugger(array('headers', 'subject', 'body'));
		
		
		var_dump($x);

		echo '<h1>end</h1>';
	}
	
	public function user_xyzAction() {
		echo '<pre>';
		
		echo 'admin user id '.config('auth.admin user id', -1).chr(10);
		echo 'admin role id '.config('auth.admin role id', -1).chr(10);
		
		var_dump(ci()->user);
		var_dump(ci()->user->roles);
		var_dump(ci()->user->permissions);
	}
	
	public function testAction() {
/*
		$this->load->library('boost');
		
		echo '<pre>';
		
		$results = $this->boost->model('o_role_model','catalog',['id','*',null,'name']);
		
		var_dump($results);
	
		$results = $this->boost->model('o_role_model','catalog',['id','name',null,'name']);
		
		var_dump($results);
	
		$results = $this->boost->model('o_role_model','catalog',['id','name,description',null,'name']);
		
		var_dump($results);
*/

		$this->page->render();
	}
	
	public function restAction() {
		$this->output->json();
	}
	
	public function refresh_administratorAction() {
		ci()->o_permission_model->administrator_refresh();
	}
		
} /* end class */