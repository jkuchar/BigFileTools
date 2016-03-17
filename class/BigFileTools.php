<?php

/**
 * Class for manipulating files bigger than 2GB
 * (currently supports only getting filesize)
 *
 * @author Honza Kuchař
 * @license New BSD
 * @encoding UTF-8
 * @copyright Copyright (c) 2013, Jan Kuchař
 */
class BigFileTools {

	/**
	 * Absolute file path
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
			throw new BigFileToolsException("You have to install BCMath or GMP. There mathematical libraries are used for size computation.");
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
	function __construct($path, $absolutizePath = true) {
		if (!static::isReadableFile($path)) {
			throw new BigFileToolsException("File not found at $path");
		}
		
		if($absolutizePath) {
			$this->setPath($path);
		}else{
			$this->setAbsolutePath($path);
		}
	}

	/**
	 * Tries to absolutize path and than updates instance state
	 * @param string $path
	 */
	function setPath($path) {
		
		$this->setAbsolutePath(static::absolutizePath($path));
	}
	
	/**
	 * Setts absolute path
	 * @param string $path
	 */
	function setAbsolutePath($path) {
		$this->path = $path;
	}
	
	/**
	 * Gets current filepath
	 * @return string
	 */
	function getPath($a = "") {
		if(a != "") {
			trigger_error("getPath with absolutizing argument is deprecated!", E_USER_DEPRECATED);
		}
		
		return $this->path;
	}
	
	/**
	 * Converts relative path to absolute
	 */
	static function absolutizePath($path) {
		
		$path = realpath($path);
		if(!$path) {
			// TODO: use hack like http://stackoverflow.com/questions/4049856/replace-phps-realpath or http://www.php.net/manual/en/function.realpath.php#84012
			//       probaly as optinal feature that can be turned on when you know, what are you doing
			
			throw new BigFileToolsException("Not possible to resolve absolute path.");
		}
		return $path;
	}
	
	static function isReadableFile($file) {
		// Do not use is_file
		// @link https://bugs.php.net/bug.php?id=27792
		// $readable = is_readable($file); // does not always return correct value for directories
		
		$fp = @fopen($file, "r"); // must be file and must be readable
		if($fp) {
			fclose($fp);
			return true;
		}
		return false;
	}

	/**
	 * Moves file to new location / rename
	 * @param string $dest
	 */
	function move($dest) {
		if (move_uploaded_file($this->path, $dest)) {
			$this->setPath($dest);
			return TRUE;
		} else {
			@unlink($dest); // needed in PHP < 5.3 & Windows; intentionally @
			if (rename($this->path, $dest)) {
				$this->setPath($dest);
				return TRUE;
			} else {
				if (copy($this->path, $dest)) {
					unlink($this->path); // delete file
					$this->setPath($dest);
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
		trigger_error("Relocate is deprecated!", E_USER_DEPRECATED);
		$this->setPath($dest);
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
	 * @throws BigFileToolsException
	 */
	public function getSize($float = false) {
		if ($float == true) {
			return (float) $this->getSize(false);
		}

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

		throw new BigFileToolsException("Can not size of file $this->path!");
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
		if (!$fp) {
			return false;
		}
		flock($fp, LOCK_SH);
		$res = fseek($fp, 0, SEEK_END);
		if ($res === 0) {
			$pos = ftell($fp);
			flock($fp, LOCK_UN);
			fclose($fp);
			// $pos will be positive int if file is <2GB
			// if is >2GB <4GB it will be negative number
			if($pos>=0) {
				return (string)$pos;
			} else {
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
		if (!$fp) {
			return false;
		}
		flock($fp, LOCK_SH);

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
			$read = strlen(fread($fp, $chunksize));
			if (self::$mathLib == self::MATH_BCMATH) {
				$size = bcadd($size, $read);
			} elseif (self::$mathLib == self::MATH_GMP) {
				$size = gmp_add($size, $read);
			} else {
				throw new BigFileToolsException("No mathematical library available");
			}
		}
		if (self::$mathLib == self::MATH_GMP) {
			$size = gmp_strval($size);
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
		// curl solution - cross platform and really cool :)
		if (function_exists("curl_init")) {
			$ch = curl_init("file://" . $this->path);
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
				$size = trim(exec("stat -Lc%s $escapedPath"));
			}

			// If the return is not blank, not zero, and is number
			if (ctype_digit($size)) {
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

class BigFileToolsException extends Exception{}