<?php

/* validation */
$config['username min length'] = 8;
$config['username max length'] = 32;
$config['password regex'] = '/((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,32})/';
$config['password copy'] = 'Password must be at least: 8 characters, 1 upper, 1 lower case letter, 1 number, Less than 32 characters';

/* Database settings */
$config['root user id'] = 1;
$config['root role id'] = 1;

$config['guest user id'] = 2;
$config['guest role id'] = 2;

$config['nobody user id'] = 3;
$config['nobody role id'] = 3;

$config['empty fields error'] = 'Please enter your login credentials.';
$config['general failure error'] = 'Incorrect Login and/or Password';
$config['account not active error'] = 'Your account is not active.';

/* user, role, permission tables */
$config['user table'] = 'orange_users';
$config['user role table'] = 'orange_user_role';
$config['role table'] = 'orange_roles';
$config['role permission table'] = 'orange_role_permission';
$config['permission table'] = 'orange_permission';
