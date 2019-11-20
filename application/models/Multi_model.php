<?php

class Multi_model extends MY_Model {
	protected $table = 'multi';
	public $empty = ['id'=>'','firstname'=>'','lastname'=>'','price'=>''];
	public $required = 'id,firstname,lastname';

	public function index()
	{

	}

	public function empty()
	{
		return ['id'=>'','modela'=>'aaa','modelb'=>'bbb'];
	}

	public function insert(array $data)
	{

	}

	public function update(array $data)
	{

	}

	public function delete(int $primary_id)
	{

	}

} /* end class */