<?php

namespace BigFileTools\Driver;
use Brick\Math\BigInteger;

/**
 * File Size driver interface
 * Every driver that support getting file size from files must implement this interface.
 * @package BigFileTools\Driver
 */
interface ISizeDriver
{
	/**
	 * Returns file size
	 * @param string $path Full path to file
	 * @return BigInteger
	 * @throws Exception
	 */
	public function getFileSize($path);
}