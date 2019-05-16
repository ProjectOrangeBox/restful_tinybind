<?php

class Robots_model extends MY_Model {
	protected $table = 'robots';
	public $columns = 'id,name,type,year,enabled,options,select';
	public $required = 'id,name,type,year';
} /* end class */