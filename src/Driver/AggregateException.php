<?php

namespace BigFileTools\Driver;

class AggregateException extends Exception
{
	/**
	 * @var Exception[]
	 */
	public $exceptions;

	/**
	 * AggregateException constructor.
	 * @param string $message
	 * @param int $code
	 * @param Exception[] $exceptions
	 */
	public function __construct($message, $code, array $exceptions)
	{
		parent::__construct($message, $code, end($exceptions));
		reset($exceptions);
		$this->exceptions = $exceptions;
	}

	/**
	 * @return Exception[]
	 */
	public function getExceptions()
	{
		return $this->exceptions;
	}
}