<?php

namespace BigFileTools\Driver;
use Brick\Math\BigInteger;

class CurlDriver implements ISizeDriver
{
	/**
	 * Returns file size by using CURL extension
	 * @inheritdoc
	 * @link http://www.php.net/manual/en/function.filesize.php#100434
	 */
	public function getFileSize($path)
	{
		// curl solution - cross platform and really cool :)
		if (function_exists("curl_init")) {
			$ch = curl_init("file://" . urlencode($path));
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, true);
			$data = curl_exec($ch);
			curl_close($ch);
			if ($data !== false && preg_match('/Content-Length: (\d+)/', $data, $matches)) {
				return BigInteger::of($matches[1]);
			}
			throw new Exception("Curl haven't returned file size.");
		} else {
			throw new Exception("Curl extension is not loaded.");
		}
	}
}