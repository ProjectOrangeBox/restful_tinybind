<?php

class TestController extends MY_Controller {
	use admin_controller_trait;

	public function indexAction() {
		$data['record'] = [
			'id'=>89,
			'repeatable'=>[
				['id'=>'45','firstname'=>'Johnny','lastname'=>'Appleseed'],
				['id'=>'78','firstname'=>'Don','lastname'=>'Jones'],
			]
		];

		ci('page')->render(null,$data);
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
