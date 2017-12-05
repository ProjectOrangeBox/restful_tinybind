<?php

class ToolsController extends MY_Controller {

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
		
		echo 'admin user id '.ADMIN_USER_ID.chr(10);
		echo 'admin role id '.ADMIN_ROLE_ID.chr(10);
		
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
	
	public function addAction() {
		$this->load->model('o_permission_model','o_role_model','o_setting_model','o_user_model');
		
		$this->_add_default_columns('orange_permissions');
		$this->_add_default_columns('orange_roles');
		$this->_add_default_columns('orange_users');
		$this->_add_default_columns('orange_settings');
	}

	/**
	 * [[Description]]
	 * @author Don Myers
	 * @return string [[Description]]
	 */
	public function _add_default_columns($tablename,$connection='default') {
		/* ALTER TABLE `www_skynet_dev`.`orange_settings` ADD COLUMN `is_deleted` TINYINT(1) UNSIGNED NULL DEFAULT 0 AFTER `internal` */
		require ROOTPATH.'/application/config/database.php';

		$config = $db[$connection];
		
		$mysqli = new mysqli($config['hostname'],$config['username'],$config['password'],$config['database']);
		
		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN read_role_id INT(11) UNSIGNED NULL DEFAULT '.ADMIN_ROLE_ID);
		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN edit_role_id INT(11) UNSIGNED NULL DEFAULT '.ADMIN_ROLE_ID);
		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN delete_role_id INT(11) UNSIGNED NULL DEFAULT '.ADMIN_ROLE_ID);

		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN created_at DATETIME NULL DEFAULT NULL');
		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN created_by INT(11) UNSIGNED NULL DEFAULT '.NOBODY_ROLE_ID);
		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN created_ip VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT \'0.0.0.0\'');

		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN updated_on DATETIME NULL DEFAULT NULL');
		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN updated_by INT(11) UNSIGNED NULL DEFAULT '.NOBODY_ROLE_ID);
		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN updated_ip VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT \'0.0.0.0\'');

		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN deleted_on DATETIME NULL DEFAULT NULL');
		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN deleted_by INT(11) UNSIGNED NULL DEFAULT '.NOBODY_ROLE_ID);
		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN deleted_ip VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT \'0.0.0.0\'');

		$mysqli->query('ALTER TABLE `'.$tablename.'` ADD COLUMN is_deleted TINYINT(1) UNSIGNED NULL DEFAULT 0');

		echo 'finished';
	}


	/**
	 * [[Description]]
	 * @author Don Myers
	 * @param	 [[Type]] [$ensure = false] [[Description]]
	 * @return [[Type]] [[Description]]
	 */
	public function drop_table($ensure = false) {
		if ($ensure !== true) {
			throw new Exception(__METHOD__ . ' please provide "true" to drop table');
		}

		return $dbforge->drop_table($this->table);
	}

	/**
	 * [[Description]]
	 * @author Don Myers
	 * @param	 [[Type]] [$ensure = false] [[Description]]
	 * @return [[Type]] [[Description]]
	 */
	public function truncate($ensure = false) {
		if ($ensure !== true) {
			throw new Exception(__METHOD__ . ' please provide "true" to truncate a database model');
		}

		return $this->_database->truncate($this->table);
	}
		
} /* end class */