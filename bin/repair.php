#!/usr/bin/env php
<?php

require 'support.inc.php';

heading('Repair file system based on composer file');

if (isset($composer_obj->orange->repair)) {
	if (is_array($composer_obj->orange->repair)) {
		foreach ($composer_obj->orange->repair as $r) {
			shell('sudo '.str_replace('{rootpath}',ESCROOTPATH,$r));
		}
	}
}
