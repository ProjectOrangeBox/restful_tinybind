<?php

class TestController extends MY_Controller {

	public function indexAction() {
		ci('page')->render();
	}

	public function foobarAction() {
		echo '<pre>';
		
		$input = '$%^ &*(IJHJ'.chr(10).'KL I*(ODon Myers.png';
		
		//$input = '10';

		$output = valid('integer',$input);
		
		var_dump($output);
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

	public function test2PostAction() {
		echo '<pre>';

		$r1 = [
			'foobar.name'=>[
				32=>'Don',
				37=>'Donna',
				39=>'Dwayne',
				42=>'Dan',
				53=>'Doug',
				72=>'David',
			],
			'foobar.first_name'=>[
				32=>'First Don',
				37=>'First Donna',
				39=>'First Dwayne',
				42=>'First Dan',
				53=>'First Doug',
				72=>'First David',
			],
			'foobar.age'=>[
				32=>12,
				37=>13,
				39=>14,
				42=>15,
				53=>16,
				72=>17,
			],
			'foobar.cookies'=>'Choc Chip',
			'foobar.food'=>'cookies',
			'candy.flavor'=>'cherry',
			'candy.color'=>'red',
			'candy.unlink'=>'good bye!',
			'firstname'=>'Don',
			'lastname'=>'Myers',
			'id'=>178,
		];

		$r2 = [
			'firstname'=>'Don',
			'lastname'=>'Myers',
			'id'=>178,
		];

		$copy = [
			'candy.parent_id'=>'id',
			'foobar[].parent_id'=>'id',
		];

		$move = [
			'candy.name'=>'candy.flavor',
			'foobar[].firstname'=>'firstname',
		];

		$remove = [
			'candy.unlink',
		];

		$x = ci('input')->request_process($copy,$move,$remove,'example_data',null,'.','_data',false);

		var_dump($x);
	}

	public function test3Action() {
		ci('page')->render();
	}

	public function test3PostAction() {
		$copy = [
			'candys.parent_id[]'=>'id',
		];
		
		$move = [
			'primary_id'=>'id',
		];
		
		$remove = [
			'color',
		];
	
		$x = ci('input')->request_remap($copy,$move,$remove,'example_data',null,'.','_data',false);

		var_dump($x);
	}

	/*
	lambda function

	$data = ['a'=>24,'b'=>89,'c'=>12,'d'=>'Chocolate Chip Cookie'];

	$field = '{a} + {b}';
	$field = 'substr({a},strlen({a}) + 1,-1)';
	$field = 'ci()->user->name';
	*/
	public function formula($field, $data=[]) {
		if (preg_match_all("/{([^}]+)}/", $field, $m)) {
			foreach ($m[1] as $value) {
				$v = (isset($data[$value])) ? $data[$value] : "''";

				$field = str_replace('{'.$value.'}',$v,$field);
			}
		}

		$func = create_function('', 'return '.$field.';');

		return $func();
	}

} /* end class */
