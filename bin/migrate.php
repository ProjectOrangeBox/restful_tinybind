#!/usr/bin/env php
<?php

require 'support.inc.php';

$dir = (isset($_SERVER['argv'][1])) ? $_SERVER['argv'][1] : 'latest';

shell('cd '.ESCROOTPATH.'/public;php index.php cli/migrate/'.$dir);
