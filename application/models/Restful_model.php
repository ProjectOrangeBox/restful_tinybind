<?php

/**
 * error: false
 * errors: []
 * form: {
 * 	action:
 * 	method:
 * }
 * page: {
 * 	action:
 * 	controller:
 * 	model:
 * 	path:
 * 	title:
 * 	titles:
 * }
 * record: {}
 * records: []
 * status: ###
 *
 */
class Restful_model
{
	public $status = 200; /* int */
	public $statusMsg = ''; /* string */
	public $payload = [];

	protected $statusMap = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		103 => 'Checkpoint',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Switch Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		449 => 'Retry With',
		450 => 'Blocked by Windows Parental Controls',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended'
	];

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		/* send back the same stuff sent to us */
		$this->payload = get_instance()->input->request();
	}

	public function __call($name, $arguments)
	{
		//var_dump($name, $arguments, count($arguments));

		switch (count($arguments)) {
			case 0:
				unset($this->payload[$name]);
				break;
			case 1:
				$this->payload[$name] = $arguments[0];
				break;
			case 2:
				$this->payload[$name][$arguments[0]] = $arguments[1];
				break;
		}

		return $this;
	}

	/**
	 * template
	 *
	 * @param string $template
	 * @return void
	 */
	public function template(string $template, int $cache_seconds = 0): Restful_model
	{
		$this->payload['template'] = ['source' => $template, 'cache' => $cache_seconds];

		return $this;
	}

	/**
	 * send
	 *
	 * @param mixed int
	 * @param mixed int
	 * @return void
	 */
	public function send(int $success = 200, int $fail = null): void
	{
		$this->payload['error'] = false;

		/* test for a fail */
		if ($fail) {
			$this->payload['error'] = get_instance()->Errors_model->has_error();

			if ($this->payload['error']) {
				$this->payload['errors'] = get_instance()->Errors_model->errors();
			}
		}

		$this->payload['status'] = ($this->payload['error']) ? $fail : $success;
		$this->payload['statusMsg'] = $this->statusMap[$this->payload['status']];

		get_instance()->output
			->enable_profiler(false)
			->set_header('Expires: Sat,26 Jul 1997 05:00:00 GMT')
			->set_header('Cache-Control: no-cache,no-store,must-revalidate,max-age=0')
			->set_header('Cache-Control: post-check=0,pre-check=0', false)
			->set_header('Pragma: no-cache')
			->set_content_type('application/json', 'utf-8')
			->set_status_header($this->payload['status'])
			->set_output(json_encode((object) $this->payload));
	}
} /* end class */
