<?php

use LightnCandy\LightnCandy;

/**
 * Whether we are in development "mode"
 */
$config['debug'] = (ENVIRONMENT == 'development');

/**
 * Handlebar flags to use
 */
$config['flags'] = LightnCandy::FLAG_HANDLEBARS | LightnCandy::FLAG_BESTPERFORMANCE | LightnCandy::FLAG_NAMEDARG | LightnCandy::FLAG_ADVARNAME | LightnCandy::FLAG_ERROR_LOG;

/**
 * Suffix all handlebar templates or partials end with
 */
$config['template extension'] = 'hbs';

/**
 * Suffix all handlebar plugins end with
 */
$config['plugin regex'] = '(.*)\.plugin\.php';

/**
 * Path to cache compiled Handlebars templates in
 */
$config['cache path'] = CACHEPATH;

/**
 * sting|array - Single or an array of path to search for partials in
 */
$config['partials path'] = '';

/**
 * sting|array - Single or an array of path to search for plugins in
 */
$config['plugin path'] = '/packages/projectorangebox/extras/libraries/plugins';
