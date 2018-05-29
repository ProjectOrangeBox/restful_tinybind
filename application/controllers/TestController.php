<?php

class TestController extends MY_Controller {
	use admin_controller_trait;

	public function indexAction() {
		echo '<pre>';

		var_dump(ci('orange_ldap')->get_all_users());

		echo 'end';
	}

	public function sindexAction() {
		$data['id'] = 89;
		$data['repeatable'] = [
			['id'=>'45','firstname'=>'Johnny','lastname'=>'Appleseed','checkers'=>0],
			['id'=>'78','firstname'=>'Don','lastname'=>'Jones','checkers'=>1],
		];

		ci('page')->render(null,$data);
	}

	public function indexPostAction() {
		ci('input')->request_remap([],[],[],'root',null,'|',true,true);



		var_dump(ci('input')->request());
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

	public function ldapAction() {
		echo '<pre>';

		//$username = 'donmyers';
		//$password = 'dsnm';

		$username = 'skynet_admin';
		$password = 'FDCQk}\'n';

		//$username = 'test';
		//$password = 'pete';

		//$success = ci('orange_ldap')->login($username,$password);

		if (!$success) {
			show_error('User Login Failed');
		}

		//$roles = ci('orange_ldap')->get_roles('js');
		//var_dump($roles);

		//$email = ci('orange_ldap')->get_email('js');
		//var_dump($email);

		//$un = ci('orange_ldap')->get_username('js');
		//var_dump($un);

		//$users = ci('orange_ldap')->get_all_users();
		var_dump($users);

		//$all = ci('orange_ldap')->all();
		//var_dump($all);
	}

} /* end class */