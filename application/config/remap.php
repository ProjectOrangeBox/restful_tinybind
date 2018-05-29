<?php

$remap['auth'] = function($config) {
	/* make auth ldap_auth which extends auth */
	ci()->auth = new ldap_auth($config);
};