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
	public function __construct(array $page)
	{
		$this->page = $page;

		/* make sure we send back what ever they gave us */
		foreach (ci('input')->request() as $key=>$val) {
			$this->$key = $val;
		}
	}

	/**
	 * errors
	 *
	 * @param mixed $errors
	 * @return void
	 */
	public function errors($errors)
	{
		$this->errors = $errors;

		return $this;
	}

	/**
	 * records
	 *
	 * @param mixed $records
	 * @return void
	 */
	public function records($records)
	{
		$this->records = $records;

		return $this;
	}

	/**
	 * record
	 *
	 * @param mixed $record
	 * @return void
	 */
	public function record($record)
	{
		$this->record = $record;

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