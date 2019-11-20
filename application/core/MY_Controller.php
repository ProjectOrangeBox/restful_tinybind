<?php

class MY_Controller extends CI_Controller {
	public $controller_path;
	public $controller_title;
	public $controller_titles;
	public $controller_model;
	public $default_view = 'index.html';
	public $data = [];

	/**
	 * _remap
	 *
	 * @param mixed $method
	 * @param mixed $params=[]
	 * @return void
	 */
	public function _remap($method,$params=[]) {
		if ($this->controller_model) {
			$this->load->model($this->controller_model);
		}

		$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
		$isJson = (!empty($_SERVER['HTTP_ACCEPT']) && strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/json') !== false);

		$method = $method.ucwords($this->input->method()).(($isAjax || $isJson) ? 'Ajax' : '');

		$this->$method(...$params);
	}

	public function indexGet() : void
	{
		$this->load->view($this->default_view);
	}

	public function indexGetAjax() : void
	{
		$this->Restful_model
			->page('title',$this->controller_title)
			->page('titles',$this->controller_titles)
			->page('path',$this->controller_path)
			->model($this->{$this->controller_model}->all());

		$this->send(200,406);
	}

	public function createGetAjax() : void
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

	public function createPostAjax() : void
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

	public function editGetAjax($id=null) : void
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

	public function editPatchAjax() : void
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

	public function deleteDeleteAjax($id=null) : void
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