<?php
/**
 * Created by PhpStorm.
 * User: jkuchar1
 * Date: 31.1.2016
 * Time: 16:23
 */

namespace BigFileTools\Driver;

use Brick\Math\BigInteger;

class AggregationSizeDriver implements ISizeDriver
{
	/**
	 * @var ISizeDriver[]
	 */
	private $drivers = [];

	/**
	 * AggregationSizeDriver constructor.
	 * @param ISizeDriver[] $drivers
	 */
	public function __construct(array $drivers)
	{
		foreach($drivers as $driver) {
			$this->addDriver($driver);
		}
	}

	private function addDriver(ISizeDriver $driver) {
		$this->drivers[] = $driver;
	}

	/**
	 * Returns file size
	 * @param string $path Full path to file
	 * @return BigInteger
	 * @throws Exception
	 */
	public function getFileSize($path)
	{
		$exceptions = [];
		foreach($this->drivers as $driver) {
			try{
				return $driver->getFileSize($path);
			} catch (Exception $exception) {
				$exceptions[] = $exception;
			}
		}

		throw new AggregateException("Cannot get file size. All methods failed. Call getPreviousExceptions() to get more information.", 0, $exceptions);
	}
}