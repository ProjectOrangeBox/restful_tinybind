<?php

class Robots_model extends MY_Model {
	protected $table = 'robots';
	public $empty = ['id'=>'','name'=>'','type'=>'Droid','year'=>'2019','enabled'=>1,'options'=>11,'select'=>2,'mselect'=>[2,"4",6]];
	public $required = 'id,name,type,year';

	/**
	 * get
	 *
	 * @param int $primary_id
	 * @return void
	 */
	public function get(int $primary_id)
	{
		$row = $this->db->get_where($this->table,[$this->primary_key=>$primary_id])->row();

		if (isset($row)) {
			$row->mselect = json_decode($row->mselect);
		} else {
			$row = false;
		}

		return $row;
	}

	/**
	 * insert
	 *
	 * @param array $data
	 * @return void
	 */
	public function insert(array $data)
	{
		if ($success = $this->check($data,false)) {
			$data['mselect'] = json_encode($data['mselect']);

			$this->db->insert($this->table, $data);

			$success = $this->db->insert_id();
		}

		return $success;
	}

	/**
	 * update
	 *
	 * @param array $data
	 * @return void
	 */
	public function update(array $data)
	{
		if ($success = $this->check($data,true)) {
			$data['mselect'] = json_encode($data['mselect']);

			$this->db->update($this->table, $data,[$this->primary_key=>$data[$this->primary_key]]);

			$success = $this->db->affected_rows();
		}

		return $success;
	}

} /* end class */