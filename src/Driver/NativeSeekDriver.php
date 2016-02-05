<?php

namespace BigFileTools\Driver;

use BigFileTools\Utils;
use Brick\Math\BigInteger;

class NativeSeekDriver implements ISizeDriver
{
	/**
	 * Returns file size by seeking at the end of file
	 * @see http://www.php.net/manual/en/function.filesize.php#79023
	 * @see http://www.php.net/manual/en/function.filesize.php#102135
	 * @param string $path Full path to file
	 * @return BigInteger
	 * @throws Exception
	 */
	public function getFileSize($path)
	{
		// This should work for large files on 64bit platforms and for small files everywhere
		$fp = fopen($path, "rb");
		if (!$fp) {
			throw new Exception("Cannot open specified file for reading.");
		}

		$flockResult = flock($fp, LOCK_SH);
		$seekResult = fseek($fp, 0, SEEK_END);
		$position = ftell($fp);
		flock($fp, LOCK_UN);
		fclose($fp);

		if($flockResult === false) {
			throw new Exception("Couldn't get file lock. Operation abandoned.");
		}

		if($seekResult !== 0) {
			throw new Exception("Seeking to end of file failed");
		}

		if($position === false) {
			throw new Exception("Cannot determine position in file. ftell() failed.");
		}

		// PHP uses internally (in C) UNSIGNED integer for file size.
		// PHP uses signed implicitly
		// convert signed (max val +2^31) -> unsigned integer will extend range for 32-bit to (+2^32)
		return BigInteger::of(
			sprintf("%u", $position)
		);
	}
}