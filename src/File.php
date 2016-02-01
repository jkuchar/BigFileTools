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
	 * @param string $absoluteFilePath **absolute** path to file
	 * @param ISizeDriver $sizeDriver Driver used for determining file size
	 */
	public function __construct($absoluteFilePath, ISizeDriver $sizeDriver)
	{
		$this->path = $absoluteFilePath;
		$this->sizeDriver = $sizeDriver;
	}

	/**
	 * @return \Brick\Math\BigInteger
	 * @throws Driver\Exception
	 */
	public function getSize()
	{
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

	/**
	 * Returns PHP's file info (some values can be incorrect due to platform limitations)
	 * @return \SplFileInfo
	 */
	public function getFileInfo()
	{
		return new \SplFileInfo($this->path);
	}

	/**
	 * Returns if file exists and is readable
	 * @return bool
	 */
	public function isReadableFile()
	{
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

	/**
	 * Moves file to new location / rename
	 * @param string $destination path
	 * @return bool
	 * @throws Exception
	 */
	function move($destination)
	{
		if (move_uploaded_file($this->path, $destination)) {
			$this->path = $destination;
			return;
		}

		@unlink($destination); // needed in PHP < 5.3 & Windows; intentionally @
		if (rename($this->path, $destination)) {
			$this->path = $destination;
			return;
		}

		if (copy($this->path, $destination)) {
			unlink($this->path); // delete file
			$this->path = $destination;
			return;
		}

		throw new Exception("File cannot be moved. All supported methods failed.");
	}
}