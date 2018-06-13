<?php

class TestController extends MY_Controller {
	use admin_controller_trait;

	public function indexAction() {
		var_dump(user::email());
	}

	public function loaded_classesAction() {
		echo '<pre>';

		var_dump(get_declared_classes());
		var_dump($this->formatBytes(memory_get_peak_usage(true)));
		var_dump($this->formatBytes(memory_get_usage(true)));
	}

	protected function formatBytes($size, $precision = 2) {
		$base = log($size, 1024);
		$suffixes = array('', 'K', 'M', 'G', 'T');

		return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
	}

	public function xindexAction() {
		echo '<pre>';

		$vd = ci('orange_ldap')->get_all_users();

/*
		$roles = [];

		foreach ($vd as $name=>$d) {
			foreach ($d['roles'] as $i=>$r) {
				$roles[$r] = $r;
			}
		}

		print_r($roles);
*/
		var_dump($vd);

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

	public function importAction() {
		$users = ci('orange_ldap')->get_all_users();

		foreach ($users as $u) {
			k($u);
		}
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

	public function jsonAction() {
		echo ci('test_model')->create_json_select('json','id,name,group,value,enabled','orange_settings');
	}

} /* end class */