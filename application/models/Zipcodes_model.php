<?php

class Zipcodes_model extends MY_Model
{
	protected $table = 'zipcodes';
	public $empty = ['id' => '', 'zipcode' => ''];
	public $required = 'id,zipcode';
} /* end class */
