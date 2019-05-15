<?php

trait rest_trait
{
	protected $respondsModel = null;

	public function indexAction() : void
	{
		ci('page')->render('/app');
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
		ci('page')->render('/app');
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
		ci('page')->render('/app');
	}

	/**
	 * layoutIndexAction
	 *
	 * @return void
	 */
	public function layoutIndexAction() : void
	{
		ci('page')->render($this->controller_path.'/index.bind');
	}

	/**
	 * layoutDetailsAction
	 *
	 * @return void
	 */
	public function layoutDetailsAction() : void
	{
		ci('page')->render($this->controller_path.'/details.bind');
	}

	/**
	 * prep JSON responds
	 *
	 * @return Restful_model
	 */
	protected function prepRespondsModel() : Restful_model
	{
		$this->respondsModel = new Restful_model;

		return $this->respondsModel
			->page([
				'path' => $this->controller_path,
				'title' => $this->controller_title,
				'titles' => $this->controller_titles,
			]);
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
			->prepRespondsModel()
			->records(ci($this->controller_model)->index())
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
			->prepRespondsModel()
			->page('action','New')
			->form('method','post')
			->form('action',$this->controller_path.'/create')
			->record(ci($this->controller_model)->empty_record())
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
		/* edit */
		$this
			->prepRespondsModel()
			->page('action','Edit')
			->form('method','patch')
			->form('action',$this->controller_path.'/edit/'.$id);

		if (!$this->respondsModel->record = ci($this->controller_model)->on_empty_return(false)->get($id)) {
			ci('errors')->add('Record not Found.');
		}

		$this->respondsModel->send(200,404);
	}

	/* Ajax verbs */

	/**
	 * indexPostAction
	 *
	 * @return void
	 */
	public function createPostAction() : void
	{
		$this->prepRespondsModel();

		if ($request = ci('input')->request('record')) {
			if ($id = ci($this->controller_model)->insert($request)) {
				$this->respondsModel->record['id'] = $id;
			}
		}

		$this->respondsModel->send(201,406);
	}

	/**
	 * indexPatchAction
	 *
	 * @return void
	 */
	public function editPatchAction() : void
	{
		$this->prepRespondsModel();

		if ($request = ci('input')->request('record')) {
			ci($this->controller_model)->update($request);
		}

		$this->respondsModel->send(202,406);
	}

	/**
	 * indexDeleteAction
	 *
	 * @return void
	 */
	public function deleteDeleteAction($id=null) : void
	{
		$this->prepRespondsModel();

		ci($this->controller_model)->delete($id);

		$this->respondsModel->send(202,406);
	}

} /* end class */
