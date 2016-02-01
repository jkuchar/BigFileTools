<?php

namespace BigFileTools\Driver;

use Brick\Math\BigInteger;

class NativeReadDriver implements ISizeDriver
{
	/**
	 * Returns file size by reading whole files and counting read bites
	 * @link http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program/5504829#5504829
	 * @inheritdoc
	 */
	public function getFileSize($path)
	{
		$fp = fopen($path, "rb");
		if (!$fp) {
			throw new Exception("Cannot read from file.");
		}
		flock($fp, LOCK_SH);

		rewind($fp);
		$offset = PHP_INT_MAX - 1;

		if (fseek($fp, $offset) !== 0) {
			flock($fp, LOCK_UN);
			fclose($fp);
			throw new Exception("Cannot seek in file");
		}

		$fileSize = BigInteger::of($offset);
		$chunkSize = 1024 * 1024;
		while (!feof($fp)) {
			$readBytes = strlen(fread($fp, $chunkSize));
			$fileSize = $fileSize->plus($readBytes);
		}
		flock($fp, LOCK_UN);
		fclose($fp);
		return $fileSize;
	}
}