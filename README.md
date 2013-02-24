BigFileTools
============

This project is response for stackoverflow question. http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program

Functionality is basic:

	<?php
	
	require "BigFileTools.php";
	$f = BigFileTools::fromFile("favoriteFilm.mkv");
	echo $f->getSize()." bytes\n";
	
This project uses BCMath or GMP for calculating big numbers. So getSize returns string.

There are server ways how to obtain proper file size for big files. (ordered by time)

	sizeCurl        0.00045299530029297
	sizeNativeSeek  0.00052094459533691
	sizeCom         0.0031449794769287
	sizeExec        0.042937040328979
	sizeNativeRead  2.7670161724091

Function getSize tries to get size using these method as in order above.
