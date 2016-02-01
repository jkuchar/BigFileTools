<?php
/**
 * Created by PhpStorm.
 * User: jkuchar1
 * Date: 31.1.2016
 * Time: 16:49
 */

namespace BigFileTools;

/**
 * Class Utils
 * @package BigFileTools
 * @internal
 */
class Utils
{
	/**
	 * Converts relative path to absolute
	 * @param string $path relative path
	 * @return string Absolute path
	 * @throws Exception
	 * @internal
	 */
	static function absolutizePath($path)
	{

		$path = realpath($path);
		if(!$path) {
			// TODO: use hack like http://stackoverflow.com/questions/4049856/replace-phps-realpath or http://www.php.net/manual/en/function.realpath.php#84012
			//       probably as optional feature that can be turned on when you know, what are you doing

			throw new Exception("Not possible to resolve absolute path.");
		}
		return $path;
	}

	/**
	 * @return bool
	 * @internal
	 */
	static function isPlatformWith32bitInteger() {
		return (string)PHP_INT_MAX === "2147483647"; // (2^31-1)
	}

	/**
	 * @return bool
	 * @internal
	 */
	static function isPlatformWith64bitInteger() {
		return (string)PHP_INT_MAX === "9223372036854775807"; // (2^63-1)
	}

}