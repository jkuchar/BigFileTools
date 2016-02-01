<?php
/**
 * Created by PhpStorm.
 * User: jkuchar1
 * Date: 31.1.2016
 * Time: 16:49
 */

namespace BigFileTools;

class Utils
{
	/**
	 * Converts relative path to absolute
	 * @param string $path relative path
	 * @return string Absolute path
	 * @throws \BigFileTools\Exception
	 */
	static function absolutizePath($path)
	{

		$path = realpath($path);
		if(!$path) {
			// TODO: use hack like http://stackoverflow.com/questions/4049856/replace-phps-realpath or http://www.php.net/manual/en/function.realpath.php#84012
			//       probaly as optinal feature that can be turned on when you know, what are you doing

			throw new Exception("Not possible to resolve absolute path.");
		}
		return $path;
	}
}