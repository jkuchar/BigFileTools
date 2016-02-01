<?php

namespace BigFileTools\Driver;

use Brick\Math\BigInteger;

class BestEffortAggregateSizeDriver implements ISizeDriver
{
	/**
	 * @var ISizeDriver[]
	 */
	private $drivers = [];
	/**
	 * @var AggregationSizeDriver
	 */
	private $aggregateDriver;

	/**
	 * @var Exception[]
	 */
	private $exceptions = [];

	/**
	 * BestEffortAggregateSizeDriverBuilder constructor.
	 * @param string[] $driverClasses name of classes to build
	 */
	public function __construct(array $driverClasses)
	{
		foreach($driverClasses as $class) {
			try{
				$this->drivers[] = new $class();
			} catch (Exception $driverException) {
				$this->exceptions[] = $driverException;
			}
		}

		$this->aggregateDriver = new AggregationSizeDriver(
			$this->getInitializedDrivers()
		);
	}

	/**
	 * @return ISizeDriver[]
	 */
	public function getInitializedDrivers()
	{
		return $this->drivers;
	}

	/**
	 * @return Exception[]
	 */
	public function getInitializationExceptions()
	{
		return $this->exceptions;
	}

	/**
	 * Returns file size
	 * @param string $path Full path to file
	 * @return BigInteger
	 * @throws Exception
	 */
	public function getFileSize($path)
	{
		return $this->aggregateDriver->getFileSize($path);
	}
}
