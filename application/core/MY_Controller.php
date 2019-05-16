<?php

class MY_Controller extends CI_Controller {
	/**
	 * _remap
	 *
	 * @param mixed $method
	 * @return void
	 */
	public function _remap($method,$arguments=[])
	{
		$this->load->model($this->controller_model);

		$http_method = $this->input->method();

		$http_method = ($http_method == 'get') ? '' : ucfirst($http_method);

		$call = $method.$http_method.'Action';

		log_message('DEBUG',$call);

		return call_user_func_array([$this,$call],$arguments);
	}

	/**
	 * indexAction
	 *
	 * @return void
	 */
	public function indexAction() : void
	{
		$this->load->view('index');
	}

	/**
	 * detailsAction
	 *
	 * HTMl Page
	 *
	 * @param mixed string
	 * @return void
	 */
	public function editAction() : void
	{
		$this->load->view('index');
	}

	/**
	 * createAction
	 *
	 * HTMl Page
	 *
	 * @return void
	 */
	public function createAction() : void
	{
		$this->load->view('index');
	}

	/**
	 * layoutIndexAction
	 *
	 * @return void
	 */
	public function layoutIndexAction() : void
	{
		$this->load->view($this->controller_path.'/index.bind.php');
	}

	/**
	 * layoutDetailsAction
	 *
	 * @return void
	 */
	public function layoutDetailsAction() : void
	{
		$this->load->view($this->controller_path.'/details.bind.php');
	}

	/* Ajax Model Request */

	/**
	 * indexJsonAction
	 *
	 * Ajax Request
	 *
	 * @return void
	 */
	public function indexModelAction() : void
	{
		$this
			->respondsModel()
			->page('title',$this->controller_title)
			->page('titles',$this->controller_titles)
			->page('path',$this->controller_path)
			->model($this->{$this->controller_model}->all())
			->send(200);
	}

	/**
	 * detailsAjaxAction
	 *
	 * @param mixed string
	 * @return void
	 */
	public function createModelAction() : void
	{
		/* new */
		$this
			->respondsModel()
			->page('title',$this->controller_title)
			->page('titles',$this->controller_titles)
			->page('path',$this->controller_path)
			->page('action','New')
			->form('method','post')
			->form('action',$this->controller_path.'/create')
			->model($this->{$this->controller_model}->empty())
			->send(200,404);
	}

	/**
	 * detailsAjaxAction
	 *
	 * @param mixed string
	 * @return void
	 */
	public function editModelAction(string $id) : void
	{
		$responds = $this->respondsModel();

		/* edit */
		$responds
			->page('title',$this->controller_title)
			->page('titles',$this->controller_titles)
			->page('path',$this->controller_path)
			->page('action','Edit')
			->form('method','patch')
			->form('action',$this->controller_path.'/edit/'.$id);

		if (!$responds->model = $this->{$this->controller_model}->get($id)) {
			$this->errors_model->add('Record not Found.');
		}

		$responds->send(200,404);
	}

	/**
	 * Ajax verbs
	 */

	/**
	 * indexPostAction
	 *
	 * @return void
	 */
	public function createPostAction() : void
	{
		$responds = $this->respondsModel();

		if ($request = $this->input->request('model')) {
			if ($id = $this->{$this->controller_model}->insert($request)) {
				$responds->model['id'] = $id;
			}
		}

		$responds->send(201,406);
	}

	/**
	 * indexPatchAction
	 *
	 * @return void
	 */
	public function editPatchAction() : void
	{
		if ($request = $this->input->request('model')) {
			$this->{$this->controller_model}->update($request);
		}

		$this->respondsModel()->send(202,406);
	}

	/**
	 * indexDeleteAction
	 *
	 * @return void
	 */
	public function deleteDeleteAction($id=null) : void
	{

		$this->{$this->controller_model}->delete($id);

		$this->respondsModel()->send(202,406);
	}

	/**
	 * respondsModel
	 *
	 * @return void
	 */
	protected function respondsModel() : Restful_model
	{
		require __DIR__.'/../models/Restful_model.php';

		return new Restful_model;
	}

} /* end class */