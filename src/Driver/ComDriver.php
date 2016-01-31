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
	/**
	 * @inheritdoc
	 * @link http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program/5502328#5502328
	 */
	public function getFileSize($path)
	{
		if (class_exists("COM")) {
			// Use the Windows COM interface
			$fsobj = new \COM('Scripting.FileSystemObject');
			if (dirname($this->path) == '.')
				$this->path = ((substr(getcwd(), -1) == DIRECTORY_SEPARATOR) ? getcwd() . basename($this->path) : getcwd() . DIRECTORY_SEPARATOR . basename($this->path));
			$f = $fsobj->GetFile($this->path);
			return BigInteger::of($f->Size);
		}
		throw new Exception("Make sure that Windows COM exception is loaded.");
	}
}
