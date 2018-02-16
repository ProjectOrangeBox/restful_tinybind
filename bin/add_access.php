#!/usr/bin/env php
<?php

require 'support.inc.php';

passthru('cd '.ESCROOTPATH.'/public;php index.php cli/auto_add_permissions');
