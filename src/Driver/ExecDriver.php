<?php

namespace BigFileTools\Driver;
use Brick\Math\BigInteger;

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

	private function getFileSizeWindows($path)
	{
		$escapedPath = escapeshellarg($path);
		$size = trim(exec("for %F in ($escapedPath) do @echo %~zF"));
		if ($size AND ctype_digit($size)) {
			return BigInteger::of($size);
		} else {
			throw new Exception("Exec returned invalid value");
		}
	}

	private function getFileSizeLinux($path)
	{
		$escapedPath = escapeshellarg($path);
		$size = trim(exec("stat -Lc%s $escapedPath"));
		if ($size AND ctype_digit($size)) {
			return BigInteger::of($size);
		} else {
			throw new Exception("Exec returned invalid value");
		}
	}

	private function getFileSizeMac($path)
	{
		$escapedPath = escapeshellarg($path);
		$size = trim(exec("stat -f%z $escapedPath"));
		if ($size AND ctype_digit($size)) {
			return BigInteger::of($size);
		} else {
			throw new Exception("Exec returned invalid value");
		}
	}
}