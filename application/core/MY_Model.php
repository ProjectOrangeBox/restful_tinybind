<?php

class MY_Model extends CI_Model {
	public $primary_key = 'id';
	public $empty = [];
	public $required = '';
	public $limit = 3000;

	/**
	 * all
	 *
	 * @return void
	 */
	public function all()
	{
		return $this->db->limit($this->limit)->get($this->table)->result();
	}

	/**
	 * empty
	 *
	 * @return void
	 */
	public function empty()
	{
		return $this->empty;
	}

	/**
	 * get
	 *
	 * @param int $primary_id
	 * @return void
	 */
	public function get(int $primary_id)
	{
		$row = $this->db->get_where($this->table,[$this->primary_key=>$primary_id])->row();

		return (isset($row)) ? $row : false;
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
			$this->db->update($this->table, $data,[$this->primary_key=>$data[$this->primary_key]]);

			$success = $this->db->affected_rows();
		}

		return $success;
	}

	/**
	 * delete
	 *
	 * @param int $primary_id
	 * @return void
	 */
	public function delete(int $primary_id)
	{
		$this->db->delete($this->table,[$this->primary_key=>$primary_id]);

		return $this->db->affected_rows();
	}

	/**
	 * check
	 *
	 * @param array $data
	 * @param bool $primaryKeyRequired
	 * @return void
	 */
	public function check(array $data,bool $primaryKeyRequired) : bool
	{
		$ci = get_instance();

		$columns = explode(',',$this->required);

		if (!$primaryKeyRequired) {
			$columns = array_diff($columns,[$this->primary_key]);
		}

		foreach ($columns as $c) {
			if (empty($data[$c])) {
				$ci->Errors_model->add(ucfirst($c).' is required.');
			}
		}

		return !$ci->Errors_model->has_error();
	}

} /* end class */