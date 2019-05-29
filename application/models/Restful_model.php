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
class Restful_model {
	public $error = false;
	public $errors = [];
	public $model = [];
	public $page = [];
	public $form = [];
	public $flags = [];
	public $config = [];
	public $status = 200; /* int */
	public $statusMsg = ''; /* string */
	public $template = ''; /* string */

	protected $CI;
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
		$this->CI = get_instance();

		$request = $this->CI->input->request();

		/* make sure we send back what ever they gave us */
		if (is_array($request)) {
			foreach ($request as $key=>$val) {
				$this->$key = $val;
			}
		}
	}

	/**
	 * flag
	 *
	 * @param mixed $name
	 * @param mixed $value
	 * @return void
	 */
	public function flag(string $name, $value) : Restful_model
	{
		$this->flags[$name] = $value;

		return $this;
	}

	/**
	 * config
	 *
	 * @param mixed $name
	 * @param mixed $value
	 * @return void
	 */
	public function config(string $name, $value) : Restful_model
	{
		$this->config[$name] = $value;

		return $this;
	}

	/**
	 * merge
	 *
	 * @param mixed &$array
	 * @param mixed $name
	 * @param mixed $value
	 * @return void
	 */
	protected function merge(&$array,$name,$value) : Restful_model
	{
		if ($value) {
			$array[$name] = $value;
		} elseif(is_array($name)) {
			foreach ($name as $n=>$v) {
				$this->merge($array,$n,$v);
			}
		} else {
			$array = $array;
		}

		return $this;
	}

	/**
	 * page
	 *
	 * @param array $array
	 * @return void
	 */
	public function page($name,$value=null) : Restful_model
	{
		return $this->merge($this->page,$name,$value);
	}

	/**
	 * page
	 *
	 * @param array $array
	 * @return void
	 */
	public function form($name,$value=null) : Restful_model
	{
		return $this->merge($this->form,$name,$value);
	}

	/**
	 * errors
	 *
	 * @param mixed $errors
	 * @return void
	 */
	public function errors(array $array) : Restful_model
	{
		$this->errors = $array;

		return $this;
	}

	/**
	 * model
	 *
	 * @param array $array
	 * @return void
	 */
	public function model(array $array) : Restful_model
	{
		$this->model = $array;

		return $this;
	}

	/**
	 * template
	 *
	 * @param string $template
	 * @return void
	 */
	public function template(string $template,int $cache_seconds = 0) : Restful_model
	{
		$this->template = ['source'=>$template,'cache'=>$cache_seconds];

		return $this;
	}

	/**
	 * send
	 *
	 * @param mixed int
	 * @param mixed int
	 * @return void
	 */
	public function send(int $success = 200,int $fail = null) : void
	{
		if ($fail) {
			$this->error = $this->CI->Errors_model->has_error();

			if ($this->error) {
				$this->errors = $this->CI->Errors_model->errors();
			}
		} else {
			$this->error = false;
		}

		$this->status = ($this->error) ? $fail : $success;
		$this->statusMsg = $this->statusMap[$this->status];

		$this->CI->output
			->enable_profiler(false)
			->set_header('Expires: Sat,26 Jul 1997 05:00:00 GMT')
			->set_header('Cache-Control: no-cache,no-store,must-revalidate,max-age=0')
			->set_header('Cache-Control: post-check=0,pre-check=0', false)
			->set_header('Pragma: no-cache')
			->set_content_type('application/json', 'utf-8')
			->set_status_header($this->status)
			->set_output($this->asJson());
	}

	/**
	 * asJson
	 *
	 * @return string
	 */
	public function asJson() : string
	{
		$payload = new StdClass;

		/* send out only the ones that aren't empty to keep the payload small */
		foreach (['error','errors','model','page','form','status','statusMsg','flags','config','template'] as $key) {
			if (!empty($this->$key)) {
				$payload->$key = $this->$key;
			}
		}

		return json_encode($payload);
	}

} /* end class */