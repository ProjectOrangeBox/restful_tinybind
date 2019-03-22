<?php

$config['account_suffix'] = env('ldap_account_suffix');
$config['domain_controllers'] = [env('ldap.secondary'),env('ldap.primary')];
$config['base_dn'] = env('ldap_base_dn');

$config['ad_port'] = 636;
$config['use_ssl'] = false;
$config['use_tls'] = true;

$config['admin_username'] = env('ldap_username');
$config['admin_password'] = env('ldap_password');
