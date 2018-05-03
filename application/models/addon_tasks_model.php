<?php

class Addon_tasks_model extends Database_model {
	protected $table = 'addon_tasks';
	protected $primary_key = 'id';
	protected $auto_generated_primary = true;
	protected $rules = [
		'id' => ['field' => 'id', 'label' => 'Id', 'rules' => 'required|filter_input'],
		'name' => ['field' => 'name', 'label' => 'Name', 'rules' => 'required|filter_input'],
		'email' => ['field' => 'email', 'label' => 'Email', 'rules' => 'required|filter_input'],
		'short_problem' => ['field' => 'short_problem', 'label' => 'Short Problem', 'rules' => 'allow_empty|filter_input'],
		'problem' => ['field' => 'problem', 'label' => 'Problem', 'rules' => 'required|filter_textarea'],
		'status' => ['field' => 'status', 'label' => 'Status', 'rules' => 'allow_empty|filter_input'],
		'notes' => ['field' => 'notes', 'label' => 'Notes', 'rules' => 'allow_empty|filter_textarea'],
		'opened' => ['field' => 'opened', 'label' => 'Opened', 'rules' => 'allow_empty|filter_input'],
		'closed' => ['field' => 'closed', 'label' => 'Closed', 'rules' => 'allow_empty|filter_input'],
		'project' => ['field' => 'project', 'label' => 'Project', 'rules' => 'allow_empty|filter_input'],
		'created_on' => ['field' => 'created_on', 'label' => 'Created On', 'rules' => 'allow_empty|filter_input'],
		'created_by' => ['field' => 'created_by', 'label' => 'Created By', 'rules' => 'allow_empty|integer|max_length[10]|less_than[4294967295]|filter_int[10]'],
		'created_ip' => ['field' => 'created_ip', 'label' => 'Created Ip', 'rules' => 'allow_empty|filter_input'],
		'updated_on' => ['field' => 'updated_on', 'label' => 'Updated On', 'rules' => 'allow_empty|filter_input'],
		'updated_by' => ['field' => 'updated_by', 'label' => 'Updated By', 'rules' => 'allow_empty|integer|max_length[10]|less_than[4294967295]|filter_int[10]'],
		'updated_ip' => ['field' => 'updated_ip', 'label' => 'Updated Ip', 'rules' => 'allow_empty|filter_input'],
	];
	protected $rule_sets = [
		'insert'=>'id,name,email,short_problem,problem,status,notes,opened,closed,project,created_on,created_by,created_ip,updated_on,updated_by,updated_ip',
	];

} /* end Addon_tasks_model */
