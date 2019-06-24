<?php

class Food_model extends MY_Model {
	protected $table = 'food';
	public $empty = ['id'=>'','firstname'=>'','lastname'=>'','price'=>''];
	public $required = 'id,firstname,lastname';
} /* end class */