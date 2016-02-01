<?php

namespace BigFileTools;
use BigFileTools\Driver\AggregationSizeDriver;
use BigFileTools\Driver\ComDriver;
use BigFileTools\Driver\CurlDriver;
use BigFileTools\Driver\ExecDriver;
use BigFileTools\Driver\ISizeDriver;
use BigFileTools\Driver\NativeSeekDriver;
use BigFileTools\Driver\BestEffortAggregateSizeDriver;

/**
 * @author Honza Kuchař
 * @license New BSD
 * @encoding UTF-8
 * @copyright Copyright (c) 2016, Jan Kuchař
 */
class BigFileTools {

	/**
	 * Create BigFileTools from $path
	 * @param string $path
	 * @return File
	 * @deprecated
	 */
	static function fromPath($path) {
		return static::createDefault()->getFile($path);
	}

	/**
	 * @var AggregationSizeDriver
	 */
	private $sizeDriver;

	/**
	 * Create new instance of BigFileTools by providing list of drivers.
	 * Those that cannot be initialized on given platform will be skipped.
	 * @param string[] $drivers
	 * @return static
	 */
	public static function createFrom(array $drivers) {
		return new static(
			new BestEffortAggregateSizeDriver($drivers)
		);
	}

	/**
	 * Create new instance of BigFileTools using default configuration.
	 * This uses default drivers ordered by speed.
	 * @return static
	 */
	public static function createDefault() {
		return static::createFrom([
			CurlDriver::class,
			NativeSeekDriver::class,
			ComDriver::class,
			ExecDriver::class,
			//NativeReadDriver::class,
		]);
	}

	/**
	 * Constructor - do not call directly
	 * @param ISizeDriver $sizeDriver
	 */
	function __construct(ISizeDriver $sizeDriver)
	{
		$this->sizeDriver = $sizeDriver;
	}

	/**
	 * Get file resource for further manipulation
	 * @param string $path **full** path to file
	 * @return File
	 */
	public function getFile($path)
	{
		return new File($path, $this->sizeDriver);
	}
}
