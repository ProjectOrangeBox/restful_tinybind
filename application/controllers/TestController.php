<?php

class TestController extends MY_Controller {
	use admin_controller_trait;

	public function indexAction() {
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

/*
You should be able to read the AD LDAP using your windows user and password since everyone
has read only access I believe. You can try and read either quad-dc1.quadratec.local
or quad-dc2.quadratec.local using port 389

The Skynet object is in the following: dc=quadratec, ou= Information Systems, cn= Skynet
*/
	public function ldap2Action() {
		set_time_limit(30);
		error_reporting(E_ALL);
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors',1);

		// config
		$ldapserver = 'quad-dc1.quadratec.local';
		$ldapuser = 'donmyers';
		$ldappass = 'dsnm';
		$ldaptree = 'dc=quadratec,dc=local,ou=Information Systems,cn=Skynet';

		// ldap://quad-dc2.quadratec.local:389/CN=Skynet,OU=Information%20Systems,DC=quadratec,DC=local

		// connect
		$ldapconn = ldap_connect($ldapserver,389) or die("Could not connect to LDAP server.");

		if ($ldapconn) {
				// binding to ldap server
				$ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));

				// verify binding
				if ($ldapbind) {
						echo "LDAP bind successful...<br /><br />";


						$result = ldap_search($ldapconn,$ldaptree, "(cn=*)") or die ("Error in search query: ".ldap_error($ldapconn));
						$data = ldap_get_entries($ldapconn, $result);

						// SHOW ALL DATA
						echo '<h1>Dump all data</h1><pre>';
						print_r($data);
						echo '</pre>';


						// iterate over array and print data for each entry
						echo '<h1>Show me the users</h1>';
						for ($i=0; $i<$data["count"]; $i++) {
								//echo "dn is: ". $data[$i]["dn"] ."<br />";
								echo "User: ". $data[$i]["cn"][0] ."<br />";
								if(isset($data[$i]["mail"][0])) {
										echo "Email: ". $data[$i]["mail"][0] ."<br /><br />";
								} else {
										echo "Email: None<br /><br />";
								}
						}
						// print number of entries found
						echo "Number of entries found: " . ldap_count_entries($ldapconn, $result);
				} else {
						echo "LDAP bind failed...";
				}

		}

		// all done? clean up
		ldap_close($ldapconn);
	}

	public function ldapAction() {
		error_reporting(E_ALL);
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors',1);

		$config = [
			'account_suffix' => '@quadratec.local',
			'domain_controllers' => ['quad-dc1.quadratec.local'],
			'base_dn' => 'dc=quadratec, ou=Information Systems, cn=Skynet',
			'admin_username' => 'donmyers',
			'admin_password' => 'dsnm',
		];

		$ad = new \Adldap\Adldap($config);

		$ad->getLdapConnection()->showErrors();

		$results = $ad->search()->all();

		echo '<pre>';

		var_dump($results);

	}

} /* end class */
