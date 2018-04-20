<?php

class MainController extends MY_Controller {

	public function indexAction() {
		ci('page')->render();
	}

	public function hijackAction($key=null) {
		assert(ctype_xdigit($key));

		$parts = explode(chr(0),hex2bin($key));

		/* must be 3 parts */
		if (count($parts) != 3) {
			ci('errors')->display(403);
		}

		/* greater than 10 minutes */
		if (date('U') - $parts[1] > 600) {
			ci('errors')->display(403);
		}

		/* check hmac */
		if ($parts[2] !== md5($parts[0].chr(0).$parts[1].chr(0).ci('config')->item('encryption_key'))) {
			ci('errors')->display(403);
		}

		ci('auth')->refresh_userdata((int)$parts[0]);

		redirect('{dashboard}');
	}

	public function test1Action() {
		$request = [
			'foobar.name'=>'Don Myers',
			'foobar.age'=>23,
			'candy.flavor'=>'cherry',
			'candy.color'=>'red',
			'firstname'=>'Don',
			'lastname'=>'Myers',
			'id'=>178,

		];

		$remap = [
			'foobar.age'=>'foobar.parent_id',
		];

		$copy = [
			'id'=>'foobar.parent_id',
			'id'=>'candy.parent_id',
		];


		$x = ci('input')->request_grouping($request,$remap,$copy,'people_model','candy');

		echo '<pre>';
		var_dump($x);
	}
	
	public function test2Action() {
		$request = [
			'foobar.name'=>[
				'Don',
				'Donna',
				'Dwayne',
				'Dan',
				'Doug',
				'David',
			],
			'foobar.age'=>[
				12,
				13,
				14,
				15,
				16,
				17,
			],
			'candy.flavor'=>'cherry',
			'candy.color'=>'red',
			'firstname'=>'Don',
			'lastname'=>'Myers',
			'id'=>178,

		];
		
		$x = ci('input')->request_dot_notation($request);

		echo '<pre>';
		var_dump($x);
	}


} /* end class */