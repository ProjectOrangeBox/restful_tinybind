<?php
/**
 * error: false
 * errors: []
 * form: {
 * 	action:
 * 	method:
 * }
 * page: {
 * 	action:
 * 	controller:
 * 	model:
 * 	path:
 * 	title:
 * 	titles:
 * }
 * record: {}
 * records: []
 * status: ###
 *
 */
class Restful_model {
	public $error = false;
	public $errors = [];
	public $record = [];
	public $records = [];
	public $page = [];
	public $form = [];
	public $status = 200;

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		/* make sure we send back what ever they gave us */
		foreach (ci('input')->request() as $key=>$val) {
			$this->$key = $val;
		}
	}

	protected function merge(&$array,$name,$value) {
		if ($value) {
			$array[$name] = $value;
		} elseif(is_array($name)) {
			foreach ($name as $n=>$v) {
				$this->merge($array,$n,$v);
			}
		} else {
			$array = $array;
		}

		return $this;
	}

	/**
	 * page
	 *
	 * @param array $array
	 * @return void
	 */
	public function page($name,$value=null)
	{
		return $this->merge($this->page,$name,$value);
	}

	/**
	 * page
	 *
	 * @param array $array
	 * @return void
	 */
	public function form($name,$value=null)
	{
		return $this->merge($this->form,$name,$value);
	}

	/**
	 * errors
	 *
	 * @param mixed $errors
	 * @return void
	 */
	public function errors(array $array)
	{
		$this->errors = $array;

		return $this;
	}

	/**
	 * records
	 *
	 * @param mixed $records
	 * @return void
	 */
	public function records(array $array)
	{
		$this->records = $array;

		return $this;
	}

	/**
	 * record
	 *
	 * @param mixed $record
	 * @return void
	 */
	public function record(array $array)
	{
		$this->record = $array;

		return $this;
	}

	/**
	 * send
	 *
	 * @param mixed int
	 * @param mixed int
	 * @return void
	 */
	public function send(int $success = 202,int $fail = 406) : void
	{
		$this->errors = ci('errors')->as_array();
		$this->error = (bool)count($this->errors);

		/*
		 * 406 Not Acceptable
		 * 200 Ok / 201 Created / 202 Accepted
		 */
		$status = ($this->error) ? $fail : $success;

		ci('output')->set_status_header($status)->json((string)$this);
	}

	/**
	 * __toString
	 *
	 * @return void
	 */
	public function __toString()
	{
		return json_encode($this);
	}

} /* end class */