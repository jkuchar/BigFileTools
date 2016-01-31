<?php
/**
 * Created by PhpStorm.
 * User: jkuchar1
 * Date: 31.1.2016
 * Time: 12:36
 */

namespace BigFileTools\Driver;

use Brick\Math\BigInteger;

class NativeSeekDriver implements ISizeDriver
{
	/**
	 * Returns file size by using native fseek function
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
			// file not readable
			// todo: throw exception?
			throw new Exception("Cannot open file for reading.");
		}
		flock($fp, LOCK_SH);
		$res = fseek($fp, 0, SEEK_END);
		// TODO: use incremental seek instead this can return wrong value on some platforms
		if ($res === 0) {
			$pos = ftell($fp);
			flock($fp, LOCK_UN);
			fclose($fp);
			// $pos will be positive int if file is <2GB
			// if is >2GB <4GB it will be negative number
			if($pos>=0) {
				return (string)$pos;
			} else {
				return sprintf("%u", $pos);
			}
		} else {
			flock($fp, LOCK_UN);
			fclose($fp);
			throw new Exception("Seeking to end of file failed");
		}
	}
}