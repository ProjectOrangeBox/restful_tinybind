<?php

$config['account_suffix'] = '@quadratec.local';
$config['domain_controllers'] = [env('ldap.secondary'),env('ldap.primary')];
$config['base_dn'] = 'DC=quadratec,DC=local'; // CN=Skynet,OU=Information Systems,DC=quadratec,DC=local

$config['admin_username'] = 'skynet_admin';
$config['admin_password'] = 'FDCQk}\'n';

/* 172.16.62.205 172.16.62.158 */
/* 'quad-dc2.quadratec.local','quad-dc1.quadratec.local' */