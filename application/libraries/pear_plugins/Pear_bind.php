<?php

class Pear_bind extends \Pear_plugin
{
	/**
	 * render
	 *
	 * @param mixed $endpoint=null
	 * @param mixed $bindjs=null
	 * @return void
	 */
	public function render($endpoint=null,$bindjs=null,$close=false) {
		$appJavascript = $bindjs.PAGE_MIN.'.js';

		$close = ($close) ? '</div>' : '';

		if (file_exists(WWW.$appJavascript)) {
			ci('page')->js(['//cdn.jsdelivr.net/npm/tinybind@0.11.0/dist/tinybind.min.js','/assets/rest_api/bound'.PAGE_MIN.'.js'],PAGE::PRIORITY_HIGHEST);
			ci('page')->js('/assets/rest_api/tinybind.stdlib'.PAGE_MIN.'.js');
			ci('page')->js($appJavascript,PAGE::PRIORITY_LOWEST);

			return '<div id="app" data-url="'.$endpoint.'">'.$close;
		} else {
			return '<div id="app"><!-- application binding '.$appJavascript.' missing -->'.$close;
		}

	}
}
