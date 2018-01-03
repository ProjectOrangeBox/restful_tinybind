<?php

class Orange_scaffolding_model extends Database_model {
	protected $table = 'orange_scaffolding';
	protected $primary_key = 'table_column';
	protected $auto_generated_primary = false;
	protected $rules = [
		'table_column' => ['field' => 'table_column', 'label' => 'Table Column', 'rules' => 'required|filter_input'],
		'human' => ['field' => 'human', 'label' => 'Human', 'rules' => 'required|filter_input'],
		'bootstrap_md_col' => ['field' => 'bootstrap_md_col', 'label' => 'Bootstrap Md Col', 'rules' => 'filter_input'],
		'details_gui' => ['field' => 'details_gui', 'label' => 'Details Gui', 'rules' => 'filter_input'],
		'index_gui' => ['field' => 'index_gui', 'label' => 'Index Gui', 'rules' => 'filter_input'],
		'override_rules' => ['field' => 'override_rules', 'label' => 'Override Rules', 'rules' => 'filter_input'],
		'additional_rules' => ['field' => 'additional_rules', 'label' => 'Additional Rules', 'rules' => 'filter_input'],
		'show_on_index' => ['field' => 'show_on_index', 'label' => 'Show On Index', 'rules' => 'filter_input'],
		'show_on_details' => ['field' => 'show_on_details', 'label' => 'Show On Details', 'rules' => 'filter_input'],
		'related_to_tablename' => ['field' => 'related_to_tablename', 'label' => 'Related To Tablename', 'rules' => 'filter_input'],
		'related_to_display_column' => ['field' => 'related_to_display_column', 'label' => 'Related To Display Column', 'rules' => 'filter_input'],
	];
	protected $rule_sets = [
		'insert'=>'table_column,human',
	];

} /* end Orange_scaffolding_model */
