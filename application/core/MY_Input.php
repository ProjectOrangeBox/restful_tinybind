<?php

class MY_Input extends CI_Input {
	protected $_request = null;

	public function request($key=null) {
		if (!$this->_request) {
			/* try to parse the input */
			parse_str($this->raw_input_stream, $this->_request);

			/* did we get anything? if not fall back to the posted input */
			if (!count($this->_request)) {
				$this->_request = $this->post();
			}
		}

		return ($key) ? (isset($this->_request[$key])) ? $this->_request[$key] : '' : $this->_request;
	}

} /* end class */