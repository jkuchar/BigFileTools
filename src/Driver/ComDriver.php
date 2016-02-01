<?php
/**
 * Created by PhpStorm.
 * User: jkuchar1
 * Date: 31.1.2016
 * Time: 12:29
 */

namespace BigFileTools\Driver;

use Brick\Math\BigInteger;

class ComDriver implements ISizeDriver
{
	public function __construct()
	{
		if (!class_exists("COM")) {
			throw new PrerequisiteException("Make sure that Windows COM exception is loaded.");
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
