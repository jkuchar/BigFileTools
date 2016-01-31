<?php

namespace BigFileTools;
use BigFileTools\Driver\AggregationSizeDriver;
use BigFileTools\Driver\ComDriver;
use BigFileTools\Driver\CurlDriver;
use BigFileTools\Driver\ExecDriver;
use BigFileTools\Driver\ISizeDriver;
use BigFileTools\Driver\NativeReadDriver;
use BigFileTools\Driver\NativeSeekDriver;
use Brick\Math\BigInteger;

/**
 * Class for manipulating files bigger than 2GB
 * (currently supports only getting filesize)
 *
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
		return (new self())->getFile($path);
	}

	/**
	 * @var AggregationSizeDriver
	 */
	private $sizeDriver;

	/**
	 * Constructor - do not call directly
	 * @param ISizeDriver $sizeDriver
	 */
	function __construct(ISizeDriver $sizeDriver = null) {
		if(!$sizeDriver) {
			$drivers = [
				new CurlDriver(),
				new NativeSeekDriver(),
				new ComDriver(),
				new ExecDriver(),
				//new NativeReadDriver()
			];
			$sizeDriver = new AggregationSizeDriver($drivers);
		}
		$this->sizeDriver = $sizeDriver;
	}

	/**
	 * Get file resource for further manipulation
	 * @param string $path **full** path to file
	 * @return File
	 */
	public function getFile($path) {
		return new File($path, $this->sizeDriver);
	}
}

class Exception extends \Exception{}