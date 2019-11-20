<?php

class Errors_model
{
	public $errors = [];

	/**
	 * add
	 *
	 * @param mixed $text
	 * @param mixed $group='default'
	 * @return void
	 */
	public function add($text, $group = 'default')
	{
		$this->errors[$group][] = $text;

		return $this;
	}

	/**
	 * has_error
	 *
	 * @return void
	 */
	public function has_error(): bool
	{
		return (bool) count($this->errors);
	}

	/**
	 * errors
	 *
	 * @return void
	 */
	public function errors(): array
	{
		return $this->errors;
	}
} /* end class */
