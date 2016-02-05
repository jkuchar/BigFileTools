<?php

namespace BigFileTools\Driver;

class SizeDriverFactory
{
	/**
	 * @var ISizeDriver[]
	 */
	private $drivers = [];

	/**
	 * @var Exception[]
	 */
	private $exceptions = [];

	/**
	 * @param string[]|callable[] $drivers names of classes to build
	 */
	public function __construct(array $drivers)
	{
		foreach($drivers as $driver) {
			try{
				if(is_callable($driver)) {
					$this->addDriver($driver());

				} else if(class_exists($driver)) {
					$this->addDriver(new $driver());

				} else {
					throw new Exception("Driver $driver cannot be initialized.");
				}
			} catch (Exception $driverException) {
				$this->exceptions[] = $driverException;
			}
		}
	}

	private function addDriver(ISizeDriver $driver) {
		$this->drivers[] = $driver;
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
}
