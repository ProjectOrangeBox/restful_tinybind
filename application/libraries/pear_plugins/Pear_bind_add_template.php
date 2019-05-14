<?php

class Pear_bind_add_template extends \Pear_plugin
{
	/**
	 * render
	 *
	 * @param mixed $endpoint=null
	 * @param mixed $bindjs=null
	 * @return void
	 */
	public function render($name=null,$id=null) {
		$html  = '<script id="'.$id.'" type="text/x-bind-template">';
		$html .= file_get_contents(ROOTPATH.'/'.$name.'.bind');
		$html .= '</script>';

		return $html;
	}
}
