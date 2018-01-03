<?php

$config['delete complete'] = false;
$config['default role id'] = 3;
$config['auto activate'] = false;

$config['email activation expire'] = 240;

$config['complete url'] = '/user-registration/complete';
$config['error url'] = '/user-registration/error';
$config['activate url'] = '/user-registration/activate';

$config['self validate email'] = true;
$config['send welcome email'] = false;

/* register */
$config['email from register'] = 'admin@example.com';
$config['email from human register'] = 'administrator';
$config['email template register'] = 'register';

/* welcome email settings */
$config['email from welcome'] = 'admin@example.com';
$config['email from human welcome'] = 'administrator';
$config['email template welcome'] = 'welcome';
