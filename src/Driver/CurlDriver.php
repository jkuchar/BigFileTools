<?php

namespace BigFileTools\Driver;
use Brick\Math\BigInteger;

class CurlDriver implements ISizeDriver
{
	public function __construct()
	{
		// curl solution - cross platform and really cool :)
		if (!function_exists("curl_init")) {
			throw new PrerequisiteException("CurlDriver requires CURL extension to be loaded in PHP");
		}
	}

	/**
	 * Returns file size by using CURL extension
	 * @inheritdoc
	 * @link http://www.php.net/manual/en/function.filesize.php#100434
	 */
	public function getFileSize($path)
	{
		$ch = curl_init("file://" . rawurlencode($path));
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		$data = curl_exec($ch);
		$size = null;
		if ($data !== false) {
			if (empty($data)) {
				$info = curl_getinfo($ch);
				if (isset($info['download_content_length'])) {
					$size = $info['download_content_length'];
				}
			} elseif (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
				$size = $matches[1];
			}
		}
		curl_close($ch);
		if ($size !== null) {
			return BigInteger::of($size);
		}
		throw new Exception("Curl haven't returned file size.");
	}
}
