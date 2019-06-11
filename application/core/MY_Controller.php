<?php

class MY_Controller extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		$this->load->model($this->controller_model);
	}

	/**
	 * index
	 *
	 * HTTP Method Get
	 *
	 * @return void
	 */
	public function index() : void
	{
		$this->Restful_model
			->page('title',$this->controller_title)
			->page('titles',$this->controller_titles)
			->page('path',$this->controller_path)
			->model($this->{$this->controller_model}->all());

		$this->send(200,406);
	}

	/**
	 * create
	 *
	 * HTTP Method Get
	 *
	 * @return void
	 */
	public function create() : void
	{
		/* new */
		$this->Restful_model
			->page('title',$this->controller_title)
			->page('titles',$this->controller_titles)
			->page('path',$this->controller_path)
			->page('action','New')
			->form('method','post')
			->form('action',$this->controller_path.'/create')
			->model($this->{$this->controller_model}->empty());

		$this->send(200,404);
	}

	/**
	 * edit
	 *
	 * HTTP Method Get
	 *
	 * @param string $id
	 * @return void
	 */
	public function edit(string $id) : void
	{
		/* edit */
		$this->Restful_model
			->page('title',$this->controller_title)
			->page('titles',$this->controller_titles)
			->page('path',$this->controller_path)
			->page('action','Edit')
			->form('method','patch')
			->form('action',$this->controller_path.'/edit/'.$id);

		if (!$this->Restful_model->model = $this->{$this->controller_model}->get($id)) {
			if (!$this->Errors_model->has_error()) {
				$this->Errors_model->add('Record not Found.');
			}
		}

		$this->send(200,404);
	}

	/**
	 * createPost
	 *
	 * HTTP Method Post
	 *
	 * @return void
	 */
	public function createPost() : void
	{
		if ($request = $this->input->request('model')) {
			if ($id = $this->{$this->controller_model}->insert($request)) {
				$this->Restful_model->model['id'] = $id;
			} else {
				if (!$this->Errors_model->has_error()) {
					$this->Errors_model->add('Error on Insert.');
				}
			}
		}

		$this->send(201,406);
	}

	/**
	 * editPatch
	 *
	 * HTTP Method Patch
	 *
	 * @return void
	 */
	public function editPatch() : void
	{
		if ($request = $this->input->request('model')) {
			$this->{$this->controller_model}->update($request);
		} else {
			if (!$this->Errors_model->has_error()) {
				$this->Errors_model->add('Error on Update.');
			}
		}

		$this->send(202,406);
	}

	/**
	 * deleteDelete
	 *
	 * HTTP Method Delete
	 *
	 * @param mixed $id=null
	 * @return void
	 */
	public function deleteDelete($id=null) : void
	{
		if ($this->{$this->controller_model}->delete($id)) {
			if (!$this->Errors_model->has_error()) {
				$this->Errors_model->add('Error on Delete.');
			}
		}

		$this->send(202,406);
	}

	/**
	 * send
	 *
	 * @param int $success
	 * @param int $fail
	 * @return void
	 */
	protected function send(int $success,int $fail) : void
	{
		$this->Restful_model->send($success,$fail);
	}

} /* end class */