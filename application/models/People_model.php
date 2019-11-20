<?php

class People_model extends MY_Model {
	protected $table = 'people';
	public $empty = ['id'=>'','firstname'=>'','lastname'=>'','price'=>''];
	public $required = 'id,firstname,lastname';
} /* end class */