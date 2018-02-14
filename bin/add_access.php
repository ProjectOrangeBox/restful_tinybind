#!/usr/bin/env php
<?php

define('ROOTPATH',realpath(__DIR__.'/../'));

passthru('cd '.str_replace(' ','\ ',ROOTPATH).'/public;php index.php cli/auto_add_permissions');
