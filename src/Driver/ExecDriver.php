<?php

namespace BigFileTools\Driver;
use Brick\Math\BigInteger;
use Brick\Math\Exception\ArithmeticException;

class ExecDriver implements ISizeDriver
{
	private $os;
	const OS_WINDOWS = "Windows";
	const OS_LINUX = "Linux";
	const OS_MAC = "Mac";

	public function __construct()
	{
		if (!function_exists("exec")) {
			throw new PrerequisiteException("Exec function is disabled");
		}

		if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
			$this->os = self::OS_WINDOWS;
		} elseif (strtoupper(PHP_OS) == "DARWIN") {
			$this->os = self::OS_MAC;
		} else {
			$this->os = self::OS_LINUX;
		}
	}

	/**
	 * Returns file size by using system shell/cmd commands
	 * @inheritdoc
	 * @link http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program/5502328#5502328
	 * @return BigInteger
	 */
	public function getFileSize($path)
	{
		switch($this->os) {
			case self::OS_WINDOWS: return $this->getFileSizeWindows($path); break;
			case self::OS_LINUX:   return $this->getFileSizeLinux($path); break;
			case self::OS_MAC:     return $this->getFileSizeMac($path); break;
			default: throw new Exception("OS not detected");
		}
	}

	/**
	 * Convert string into integer
	 * Must be precise number, otherwise you will see and exception.
	 *
	 * @param $valueAsString
	 * @return BigInteger
	 * @throws Exception
	 */
	private function convertToInteger($valueAsString) {
		if(!is_string($valueAsString)) {
			throw new Exception("Cannot convert to integer. Expected string, but got " . gettype($valueAsString). ".");
		}
		$trimmedInput = trim($valueAsString);

		try {
			return BigInteger::of($trimmedInput);

		} catch (ArithmeticException $e) {
			throw new Exception("Returned value cannot be converted to an integer.",0, $e);
		}

	}

	private function getFileSizeWindows($path)
	{
		$escapedPath = escapeshellarg($path);
		return $this->convertToInteger(
			exec("for %F in ($escapedPath) do @echo %~zF")
		);
	}

	private function getFileSizeLinux($path)
	{
		$escapedPath = escapeshellarg($path);
		return $this->convertToInteger(
			exec("stat -Lc%s $escapedPath")
		);
	}

	private function getFileSizeMac($path)
	{
		$escapedPath = escapeshellarg($path);
		return $this->convertToInteger(
			exec("stat -f%z $escapedPath")
		);
	}
}