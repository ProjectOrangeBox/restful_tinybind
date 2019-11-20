<?php

class RobotCatalogs_model extends MY_Model {
	protected $table = 'robot_catalog';
	public $empty = ['id'=>'','value'=>''];
	public $required = 'id,value';
} /* end class */