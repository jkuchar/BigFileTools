<?php

namespace BigFileTools\Driver;

use Brick\Math\BigInteger;

class ComDriver implements ISizeDriver
{
	public function __construct()
	{
		if (!class_exists("COM")) {
			throw new PrerequisiteException("ComDriver requires COM extension to be loaded in PHP");
		}
	}

	/**
	 * Returns file size by using Windows COM interface
	 * @inheritdoc
	 * @link http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program/5502328#5502328
	 */
	public function getFileSize($path)
	{
		// Use the Windows COM interface
		$fsobj = new \COM('Scripting.FileSystemObject');
		if (dirname($path) == '.')
			$this->path = ((substr(getcwd(), -1) == DIRECTORY_SEPARATOR) ? getcwd() . basename($path) : getcwd() . DIRECTORY_SEPARATOR . basename($path));
		$f = $fsobj->GetFile($path);
		return BigInteger::of($f->Size);
	}
}
