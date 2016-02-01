<?php

namespace BigFileTools\Driver;

use Brick\Math\BigInteger;

/**
 * Aggregates results from more ISizeDrivers
 * @package BigFileTools\Driver
 */
class AggregationSizeDriver implements ISizeDriver
{
	/**
	 * @var ISizeDriver[]
	 */
	private $drivers = [];

	/**
	 * @var ISizeDriver
	 */
	private $lastUsedDriver = null;

	/**
	 * @var Exception[]
	 */
	private $lastExceptions = [];

	/**
	 * AggregationSizeDriver constructor.
	 * @param ISizeDriver[] $drivers Ordered array of drivers
	 */
	public function __construct(array $drivers)
	{
		foreach($drivers as $driver) {
			$this->addDriver($driver);
		}
	}

	private function addDriver(ISizeDriver $driver)
	{
		$this->drivers[] = $driver;
	}

	/**
	 * Returns first non-failing driver from last used of getFileSize()
	 * @return ISizeDriver
	 * @internal Should be used for debugging only
	 */
	public function getLastUsedDriver()
	{
		return $this->lastUsedDriver;
	}

	/**
	 * Returns file size
	 * @param string $path Full path to file
	 * @return BigInteger
	 * @throws Exception
	 */
	public function getFileSize($path)
	{
		$this->lastExceptions = [];
		foreach($this->drivers as $driver) {
			try{
				$result = $driver->getFileSize($path);
				$this->lastUsedDriver = $driver;
				return $result;
			} catch (Exception $exception) {
				$this->lastExceptions[] = $exception;
			}
		}

		throw new AggregateException(
			"Cannot get file size. All methods failed. Call getPreviousExceptions() to get more information.",
			null,
			$this->lastExceptions
		);
	}
}
