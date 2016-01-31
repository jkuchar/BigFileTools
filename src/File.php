<?php

namespace BigFileTools;

use BigFileTools\Driver\ISizeDriver;

class File
{
	/**
	 * Full path to file
	 * @var string
	 */
	private $path;

	/**
	 * @var ISizeDriver
	 */
	private $sizeDriver;

	/**
	 * File constructor.
	 * @param string $path
	 * @param ISizeDriver $sizeDriver
	 */
	public function __construct($path, ISizeDriver $sizeDriver)
	{
		$this->path = $path;
		$this->sizeDriver = $sizeDriver;
	}

	/**
	 * @return \Brick\Math\BigInteger
	 * @throws Driver\Exception
	 */
	public function getSize() {
		return $this->sizeDriver->getFileSize($this->path);
	}

	/**
	 * Absolute path to file
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	public function getFileInfo() {
		return new \SplFileInfo($this->path);
	}

	/**
	 * Returns if file exists and is readable
	 * @return bool
	 */
	public function isReadableFile() {
		// Do not use is_file
		// @link https://bugs.php.net/bug.php?id=27792
		// $readable = is_readable($file); // does not always return correct value for directories

		$fp = @fopen($this->path, "r"); // must be file and must be readable
		if($fp) {
			fclose($fp);
			return true;
		}
		return false;
	}


//	/**
//	 * Moves file to new location / rename
//	 * @param string $dest
//	 * @return bool
//	 */
//	function move($dest) {
//		if (move_uploaded_file($this->path, $dest)) {
//			$this->setPath($dest);
//			return TRUE;
//		} else {
//			@unlink($dest); // needed in PHP < 5.3 & Windows; intentionally @
//			if (rename($this->path, $dest)) {
//				$this->setPath($dest);
//				return TRUE;
//			} else {
//				if (copy($this->path, $dest)) {
//					unlink($this->path); // delete file
//					$this->setPath($dest);
//					return TRUE;
//				}
//				return FALSE;
//			}
//		}
//	}
}