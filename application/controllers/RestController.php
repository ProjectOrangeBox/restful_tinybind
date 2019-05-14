<?php
/**
 * Work in progress
 */
class RestController extends \MY_Controller
{
	public $controller					= 'Rest';
	public $controller_path			= '/rest';
	public $controller_model		= 'Robots_model';
	public $controller_title		= 'Robot';
	public $controller_titles		= 'Robots';

	protected $_restfulObject = null;

	public function indexAction() : void
	{
		ci('page')
			->js([
				'//cdn.jsdelivr.net/npm/tinybind@0.11.0/dist/tinybind.min.js',

				'//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js',
				'//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js',
				'//cdnjs.cloudflare.com/ajax/libs/tinysort/3.1.4/tinysort.js',

				'/theme/orange/assets/plugins/notify/notify.min.js',
				'/theme/orange/assets/plugins/flash-msg/jquery.bootstrap.flash-msg.min.js',

				'/theme/orange/assets/plugins/bound-table-search/bound-table-search.min.js',
				'/theme/orange/assets/plugins/table_sort/table_sort.min.js',
				'/theme/orange/assets/plugins/table_remember_position/table_remember_position.min.js',
				'/theme/orange/assets/plugins/table_sticky_header/jquery.stickytableheaders.min.js',

				'/restful-app/tinybind.stdlib.min.js',
				'/restful-app/grape.min.js',
				'/restful-app/myapp.min.js',
			])
			->css([
				'/theme/orange/assets/plugins/table_sort/table_sort.min.css',

				'/theme/orange/assets/plugins/flash-msg/flash-msg.min.css',
				'/theme/orange/assets/plugins/notify/notify.min.css',
			]);

		ci('page')->render('/rest/template');
	}

	public function layoutIndexAction() : void
	{
		ci('page')->render('/rest/index.bind');
	}

	public function layoutDetailsAction() : void
	{
		ci('page')->render('/rest/details.bind');
	}

	/**
	 * _prepareRestfulResponds
	 *
	 * @return void
	 */
	protected function _prepareRestfulResponds() : void
	{
		$this->_restfulObject = new Restful_model([
			'controller' => $this->controller,
			'path' => $this->controller_path,
			'model' => $this->controller_model,
			'title' => $this->controller_title,
			'titles' => $this->controller_titles,
		]);
	}

	/**
	 * detailsAction
	 *
	 * HTMl Page
	 *
	 * @param mixed string
	 * @return void
	 */
	public function editAction(string $id = null) : void
	{
		$this->indexAction();
		//$this->htmlRender($this->controller_path.'/details','edit',$id);
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
		$this->indexAction();
		//$this->htmlRender($this->controller_path.'/details','create');
	}

	protected function htmlRender(string $template = null,string $key,$id = '') : void
	{
		/*
		ci('page')->render($template,[
			'endpoint'=>sprintf($this->restEndPoints[$key][0],$this->controller_path,$id),
			'bindjs'=>sprintf($this->restEndPoints[$key][1],$this->controller_path,$id),
		]);
		*/
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
		$this->_prepareRestfulResponds();

		$this->_restfulObject->records(ci($this->controller_model)->index())->send(200);
	}

	/**
	 * detailsAjaxAction
	 *
	 * @param mixed string
	 * @return void
	 */
	public function createModelAction() : void
	{
		$this->_prepareRestfulResponds();

		/* new */
		$this->_restfulObject->page['action'] = 'New';
		$this->_restfulObject->form['method'] = 'post';
		$this->_restfulObject->form['action'] = $this->controller_path.'/create';

		$this->_restfulObject->record(ci($this->controller_model)->empty_record())->send(200,404);
	}

	/**
	 * detailsAjaxAction
	 *
	 * @param mixed string
	 * @return void
	 */
	public function editModelAction(string $id) : void
	{
		$this->_prepareRestfulResponds();

		/* edit */
		$this->_restfulObject->page['action'] = 'Edit';
		$this->_restfulObject->form['method'] = 'patch';
		$this->_restfulObject->form['action'] = $this->controller_path.'/edit/'.$id;

		if (!$this->_restfulObject->record = ci($this->controller_model)->on_empty_return(false)->get($id)) {
			ci('errors')->add('Record not Found.');
		}

		$this->_restfulObject->send(200,404);
	}

	/* Ajax verbs */

	/**
	 * indexPostAction
	 *
	 * @return void
	 */
	public function createPostAction() : void
	{
		$this->_prepareRestfulResponds();

		if ($request = ci('input')->request('record')) {
			if ($id = ci($this->controller_model)->insert($request)) {
				$this->_restfulObject->record['id'] = $id;
			}
		}

		$this->_restfulObject->send(201,406);
	}

	/**
	 * indexPatchAction
	 *
	 * @return void
	 */
	public function editPatchAction() : void
	{
		$this->_prepareRestfulResponds();

		if ($request = ci('input')->request('record')) {
			ci($this->controller_model)->update($request);
		}

		$this->_restfulObject->send(202,406);
	}

	/**
	 * indexDeleteAction
	 *
	 * @return void
	 */
	public function deleteDeleteAction($id=null) : void
	{
		$this->_prepareRestfulResponds();

		ci($this->controller_model)->delete($id);

		$this->_restfulObject->send(202,406);
	}

	/**
	 * seedAction
	 *
	 * @return void
	 */
	public function seedAction($number=100) : void
	{
		for ($i = 1; $i <= $number; $i++) {
			ci('Robots_model')->seed();
		}
	}

} /* end class */
