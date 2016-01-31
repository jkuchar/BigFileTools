<?php

namespace BigFileTools\Driver;
use Brick\Math\BigInteger;

class ExecDriver implements ISizeDriver
{
	/**
	 * @inheritdoc
	 * @link http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program/5502328#5502328
	 */
	public function getFileSize($path)
	{
		if (function_exists("exec")) {
			$escapedPath = escapeshellarg($path);

			if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') { // Windows
				// Try using the NT substitution modifier %~z
				$size = trim(exec("for %F in ($escapedPath) do @echo %~zF"));
			}else{ // other OS
				// If the platform is not Windows, use the stat command (should work for *nix and MacOS)
				$size = trim(exec("stat -Lc%s $escapedPath"));
			}

			// If the return is not blank, not zero, and is number
			if ($size AND ctype_digit($size)) {
				return BigInteger::of($size);
			} else {
				throw new Exception("Exec returned invalid value");
			}
		}
		throw new Exception("Exec function is disabled");
	}
}