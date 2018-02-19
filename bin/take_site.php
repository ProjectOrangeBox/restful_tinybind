#!/usr/bin/env php
<?php

require 'support.inc.php';

switch (get_arg1(false)) {
	case 'up':
		heading('Site Up');

		exec('sudo chmod 777 '.ESCROOTPATH.'/public');
		@unlink(ROOTPATH.'/public/index.html');
		exec('sudo chmod 775 '.ESCROOTPATH.'/public');
	break;
	case 'down':
		heading('Site Down');
		
		exec('sudo chmod 777 '.ESCROOTPATH.'/public');
		file_put_contents(ROOTPATH.'/public/index.html',down_html());
		exec('sudo chmod 775 '.ESCROOTPATH.'/public');
	break;
	default:
		error('please provide up or down');
}
