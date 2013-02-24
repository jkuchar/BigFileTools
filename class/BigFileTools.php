<?php

/**
 * Class for manipulating files bigger than 2GB
 *
 * @author Honza Kuchař
 * @license LGPL
 * @encoding UTF-8
 * @copyright Copyright (c) 2011, Jan Kuchař
 * @editor NetBeans
 */
class BigFileTools extends Nette\Object {

	/**
	 * File path
	 * @var string
	 */
	protected $path;

	/**
	 * Use in BigFileTools::$mathLib if you want to use BCMath for mathematical operations
	 */
	const MATH_BCMATH = "BCMath";

	/**
	 * Use in BigFileTools::$mathLib if you want to use GMP for mathematical operations
	 */
	const MATH_GMP = "GMP";

	/**
	 * Which mathematical library use for mathematical operations
	 * @var string (on of constants BigFileTools::MATH_*)
	 */
	public static $mathLib;
	
	/**
	 * If none of fast modes is available to compute filesize, BigFileTools uses to compute size very slow
	 * method - reading file from 0 byte to end. If you want to enable this behavior,
	 * switch fastMode to false (default is true)
	 * @var bool
	 */
	public static $fastMode = true;

	/**
	 * Initialization of class
	 * Do not call directly.
	 */
	static function init() {
		if (function_exists("bcadd")) {
			self::$mathLib = self::MATH_BCMATH;
		} elseif (function_exists("gmp_add")) {
			self::$mathLib = self::MATH_GMP;
		} else {
			throw new \Nette\InvalidStateException("You must have installed one of there mathematical libraries: BC Math or GMP!");
		}
	}

	/**
	 * Create BigFileTools from $path
	 * @param string $path
	 * @return BigFileTools
	 */
	static function fromPath($path) {
		return new self($path);
	}

	/**
	 * Gets basename of file (example: for file.txt will return "file")
	 * @return string
	 */
	public function getBaseName() {
		return pathinfo($this->path, PATHINFO_BASENAME);
	}

	/**
	 * Gets extension of file (example: for file.txt will return "txt")
	 * @return string
	 */
	public function getExtension() {
		return pathinfo($this->path, PATHINFO_EXTENSION);
	}


	/**
	 * Gets extension of file (example: for file.txt will return "file.txt")
	 * @return string
	 */
	public function getFilename() {
		return pathinfo($this->path, PATHINFO_FILENAME);
	}

	/**
	 * Gets path to file of file (example: for file.txt will return path to file.txt, e.g. /home/test/)
	 * ! This will call absolute path!
	 * @return string
	 */
	public function getDirname() {
		$this->absolutizePath();
		return pathinfo($this->path, PATHINFO_DIRNAME);
	}

	/**
	 * Gets md5 checksum of file content
	 * @return string
	 */
	public function getMd5() {
		return md5_file($this->path);
	}

	/**
	 * Gets sha1 checksum of file content
	 * @return string
	 */
	public function getSha1() {
		return sha1_file($this->path);
	}

	/**
	 * Constructor - do not call directly
	 * @param string $path
	 */
	function __construct($path) {
		if (!file_exists($path) OR !is_file($path)) {
			throw new Exception("File not found at $path");
		}
		$this->path = $path;
	}

	/**
	 * Gets current filepath
	 * @return string
	 */
	function getPath($absolutize = false) {
		if ($absolutize) {
			$this->absolutizePath();
		}
		return $this->path;
	}

	/**
	 * Converts relative path to absolute
	 */
	function absolutizePath() {
		return $this->path = realpath($this->path);
	}

	/**
	 * Moves file to new location
	 * @param string $dest
	 */
	function move($dest) {
		if (move_uploaded_file($this->path, $dest)) {
			$this->path = $dest;
			return TRUE;
		} else {
			@unlink($dest); // needed in PHP < 5.3 & Windows; intentionally @
			if (rename($this->path, $dest)) {
				$this->path = $dest;
				return TRUE;
			} else {
				if (copy($this->path, $dest)) {
					unlink($this->path);
					$this->path = $dest;
					return TRUE;
				}
				return FALSE;
			}
		}
	}

	/**
	 * Changes path of this file object
	 * @param string $dest
	 */
	function relocate($dest) {
		$this->path = $dest;
	}

	/**
	 * Size of file
	 *
	 * Profiling results:
	 *  sizeCurl        0.00045299530029297
	 *  sizeNativeSeek  0.00052094459533691
	 *  sizeCom         0.0031449794769287
	 *  sizeExec        0.042937040328979
	 *  sizeNativeRead  2.7670161724091
	 *
	 * @return string | float
	 * @throws InvalidStateException
	 */
	public function getSize($float = false) {
		if ($float == true) {
			return (float) $this->getSize(false);
		}
		$this->absolutizePath();

		$return = $this->sizeCurl();
		if ($return) {
			return $return;
		}

		$return = $this->sizeNativeSeek();
		if ($return) {
			return $return;
		}

		$return = $this->sizeCom();
		if ($return) {
			return $return;
		}

		$return = $this->sizeExec();
		if ($return) {
			return $return;
		}

		if (!self::$fastMode) {
			$return = $this->sizeNativeRead();
			if ($return) {
				return $return;
			}
		}

		throw new InvalidStateException("Can not size of file $this->path!");
	}

	// <editor-fold defaultstate="collapsed" desc="size* implementations">
	/**
	 * Returns file size by using native fseek function
	 * @see http://www.php.net/manual/en/function.filesize.php#79023
	 * @see http://www.php.net/manual/en/function.filesize.php#102135
	 * @return string | bool (false when fail)
	 */
	protected function sizeNativeSeek() {
		// This should work for large files on 64bit platforms and for small files every where
		$fp = fopen($this->path, "rb");
		flock($fp, LOCK_SH);
		if (!$fp) {
			return false;
		}
		$res = fseek($fp, 0, SEEK_END);
		if ($res === 0) {
			$pos = ftell($fp);
			flock($fp, LOCK_UN);
			fclose($fp);
			// $pos will be positive int if file is <2GB
			// if is >2GB <4GB it will be negative number
			if($pos>=0) {
				return (string)$pos;
			}else{
				return sprintf("%u", $pos);
			}
		} else {
			flock($fp, LOCK_UN);
			fclose($fp);
			return false;
		}
	}

	/**
	 * Returns file size by using native fread function
	 * @see http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program/5504829#5504829
	 * @return string | bool (false when fail)
	 */
	protected function sizeNativeRead() {
		$fp = fopen($this->path, "rb");
		flock($fp, LOCK_SH);
		if (!$fp) {
			return false;
		}

		rewind($fp);
		$offset = PHP_INT_MAX - 1;

		$size = (string) $offset;
		if (fseek($fp, $offset) !== 0) {
			flock($fp, LOCK_UN);
			fclose($fp);
			return false;
		}
		$chunksize = 1024 * 1024;
		while (!feof($fp)) {
			$readed = strlen(fread($fp, $chunksize));
			if (self::$mathLib == self::MATH_BCMATH) {
				$size = bcadd($size, $readed);
			} elseif (self::$mathLib == self::MATH_GMP) {
				$size = gmp_add($size, $readed);
			} else {
				throw new \Nette\InvalidStateException("No mathematical library available");
			}
		}
		if (self::$mathLib == self::MATH_GMP) {
			gmp_strval($size);
		}
		flock($fp, LOCK_UN);
		fclose($fp);
		return $size;
	}

	/**
	 * Returns file size using curl module
	 * @see http://www.php.net/manual/en/function.filesize.php#100434
	 * @return string | bool (false when fail or cUrl module not available)
	 */
	protected function sizeCurl() {
		// If program goes here, file must be larger than 2GB
		// curl solution - cross platform and really cool :)
		if (function_exists("curl_init")) {
			$ch = curl_init("file://" . realpath($this->path));
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, true);
			$data = curl_exec($ch);
			curl_close($ch);
			if ($data !== false && preg_match('/Content-Length: (\d+)/', $data, $matches)) {
				return (string) $matches[1];
			}
		} else {
			return false;
		}
	}

	/**
	 * Returns file size by using external program (exec needed)
	 * @see http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program/5502328#5502328
	 * @return string | bool (false when fail or exec is disabled)
	 */
	protected function sizeExec() {
		// filesize using exec
		if (function_exists("exec")) {
			$escapedPath = escapeshellarg($this->path);

			if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') { // Windows
				// Try using the NT substition modifier %~z
				$size = trim(exec("for %F in ($escapedPath) do @echo %~zF"));
			}else{ // other OS
				// If the platform is not Windows, use the stat command (should work for *nix and MacOS)
				$size = trim(exec("stat -c%s $escapedPath"));
			}

			// If the return is not blank, not zero, and is number
			if ($size AND ctype_digit($size)) {
				return (string) $size;
			}
		}
		return false;
	}

	/**
	 * Returns file size by using Windows COM interface
	 * @see http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program/5502328#5502328
	 * @return string | bool (false when fail or COM not available)
	 */
	protected function sizeCom() {
		if (class_exists("COM")) {
			// Use the Windows COM interface
			$fsobj = new COM('Scripting.FileSystemObject');
			if (dirname($this->path) == '.')
				$this->path = ((substr(getcwd(), -1) == DIRECTORY_SEPARATOR) ? getcwd() . basename($this->path) : getcwd() . DIRECTORY_SEPARATOR . basename($this->path));
			$f = $fsobj->GetFile($this->path);
			return (string) $f->Size;
		}
	}

	// </editor-fold>
}

BigFileTools::init();
