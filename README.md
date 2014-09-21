BigFileTools
============
[![Code Climate](https://codeclimate.com/github/jkuchar/BigFileTools/badges/gpa.svg)](https://codeclimate.com/github/jkuchar/BigFileTools)

Project that allows you to manipulate huge files in PHP. (currently supports only getting file size)

This project was (originally) response for stackoverflow question. http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program

Example:

	<?php
	
	require "BigFileTools.php";
	$f = BigFileTools::fromFile("favoriteFilm.mkv");
	echo $f->getSize()." bytes\n";
	
	?>
	
This project uses BCMath or GMP for calculating big numbers. (automatically chosen) So getSize returns string for maximal precision.

There are several ways how to get proper file size for big files in PHP. (ordered by runtime)

	method          time
	------          ----
	sizeCurl        0.00045299530029297
	sizeNativeSeek  0.00052094459533691
	sizeCom         0.0031449794769287
	sizeExec        0.042937040328979
	sizeNativeRead  2.7670161724091

getSize() tries to get size using these method as in order above to be as fast as possible on your platform.

Requirements
------------

This class is designed to use with Nette. However it is really simple to remove this dependency. If you want to do that follow first comment in BigFileTools.php.
