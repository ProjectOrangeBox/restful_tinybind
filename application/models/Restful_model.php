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
	public $status = 200;
	public $statusMsg = '';

	private $CI;
	private $statusMap = [
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

	protected function merge(&$array,$name,$value) {
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
	public function page($name,$value=null)
	{
		return $this->merge($this->page,$name,$value);
	}

	/**
	 * page
	 *
	 * @param array $array
	 * @return void
	 */
	public function form($name,$value=null)
	{
		return $this->merge($this->form,$name,$value);
	}

	/**
	 * errors
	 *
	 * @param mixed $errors
	 * @return void
	 */
	public function errors(array $array)
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
	public function model(array $array)
	{
		$this->model = $array;

		return $this;
	}

	/**
	 * send
	 *
	 * @param mixed int
	 * @param mixed int
	 * @return void
	 */
	public function send(int $success = 202,int $fail = 406) : void
	{
		$this->errors = $this->CI->Errors_model->errors();
		$this->error = $this->CI->Errors_model->has_error();

		/*
		 * 406 Not Acceptable
		 * 200 Ok / 201 Created / 202 Accepted
		 */
		$this->status = ($this->error) ? $fail : $success;
		$this->statusMsg = $this->statusMap[$this->status];

		get_instance()->output
			->enable_profiler(false)
			->set_header('Expires: Sat,26 Jul 1997 05:00:00 GMT')
			->set_header('Cache-Control: no-cache,no-store,must-revalidate,max-age=0')
			->set_header('Cache-Control: post-check=0,pre-check=0', false)
			->set_header('Pragma: no-cache')
			->set_content_type('application/json', 'utf-8')
			->set_status_header($this->status)
			->set_output((string)$this);
	}

	/**
	 * __toString
	 *
	 * @return void
	 */
	public function __toString()
	{
		$payload = new StdClass;

		/* send out only the ones that aren't empty to keep the payload small */
		foreach (['error','errors','model','page','form','status','statusMsg'] as $key) {
			if (!empty($this->$key)) {
				$payload->$key = $this->$key;
			}
		}

		return json_encode($payload);
	}

} /* end class */