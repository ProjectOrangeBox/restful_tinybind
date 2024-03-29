<?php

class Robots_model extends MY_Model
{
	protected $table = 'robots';
	public $empty = ['id' => '', 'name' => '', 'type' => 'Droid', 'year' => '2019', 'enabled' => 0, 'options' => 11, 'select' => 2, 'mselect' => [2, "4", 6]];
	public $required = 'id,name,type,year';

	/**
	 * get
	 *
	 * @param int $primary_id
	 * @return void
	 */
	public function get(int $primary_id)
	{
		$row = $this->db->get_where($this->table, [$this->primary_key => $primary_id])->row();

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
		if ($success = $this->check($data, false)) {
			$data['mselect'] = json_encode($data['mselect']);

			$success = parent::insert($data);
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
		if ($success = $this->check($data, true)) {
			$data['mselect'] = json_encode($data['mselect']);

			$success = parent::update($data);
		}

		return $success;
	}
} /* end class */
