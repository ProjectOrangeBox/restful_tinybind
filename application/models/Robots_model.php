<?php

class Robots_model extends MY_Model {
	protected $table = 'robots';
	public $empty = ['id'=>'','name'=>'','type'=>'Droid','year'=>'2019','enabled'=>1,'options'=>11,'select'=>2];
	public $required = 'id,name,type,year';
} /* end class */