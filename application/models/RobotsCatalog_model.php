<?php

class RobotsCatalog_model extends Database_Model {
	protected $table = 'robot_catalog';
	protected $rules = [
		'id'=>['field'=>'id','label'=>'Id','rules'=>'required|integer|max_length[20]|less_than[4294967295]|filter_int[20]'],
		'value'=>['field'=>'value','label'=>'Value','rules'=>'required|filter_input'],
	];
	protected $rule_sets = [
		'insert'=>'value',
		'update'=>'id,value',
	];
} /* end class */