<?php

$config['file path'] = '/var/uploads/emarsys_order_data_%s.csv';
$config['log path'] = '/var/logs/emarsys_order_data_%s.log';

$config['bearer token'] = env('emarsys_order_feed');
$config['end point'] = 'https://admin.scarabresearch.com/hapi/merchant/188D770212F227E4/sales-data/api';

$config['keep days'] = 7;

$config['logs glob'] = '/var/logs/emarsys_order_data_*.log';
$config['upload glob'] = '/var/uploads/emarsys_order_data_*.csv';
