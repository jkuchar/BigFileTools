# BigFileTools #


This project is collection of hacks that are needed to manipulate files over 2GB in PHP. Currently there is support for getting **exact file size**. This project is originally answer for [stackoverflow question](http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program). 

Example usage:
````php
require "BigFileTools.php";

$size = BigFileTools::fromPath(__FILE__)->getSize();
var_dump($size);
echo "Example files size is " . $size . " bytes\n";
````
Will produce output:
````
string(3) "176"
Example files size is 176 bytes
````
Please note, that getSize returns `string`. This is due to fact we cannot be sure that php-integer will be able to store that big value.

## Under the hood ##

To get insight into what is happening we need a little introduction in how numbers are represented in digital world.

The problem lies in the fact that PHP uses 32-bit signed integer on most platforms. PHPx64 for Linux 64-bit version uses 64-bit so you do not need this library there anymore. On the other hand 64-bit version of PHP for 64-bit Windows uses 32-bit integer. Because PHP uses signed integers this means that there is one bit for sign (+-) and rest is used for value.

````
32-bit signed integer max value: +2^31 =             2 147 483 648 ~             2 gigabytes
64-bit signed integer max value: +2^63 = 9 223 372 036 854 775 808 ~ 9 223 372 000 gigabytes
````

To overcome this problem this library uses string representation of numbers which means that only you RAM is limit of number size.

**Caution:** There are tons of non-solutions for this problem. Most of them looks like `sprintf("%u", filesize($file));`. This does NOT solve problem. If just shifts it a little. The `%u` assumes give value as **unsigned** integer. This means that first signing bit is treated also as a value. Unfortunately this means that boundary was just shifted from 2 GB limit to 4 GB. 

Second problem is that standard file manipulation APIs fails with strange errors or returns completely weird values. Library therefore implements more of doing things and tries what works on your platform. They are executed from the fastest to the slowest method. On my test data results were:


	method          time
	------          ----
	sizeCurl        0.00045299530029297
	sizeNativeSeek  0.00052094459533691
	sizeCom         0.0031449794769287
	sizeExec        0.042937040328979
	sizeNativeRead  2.7670161724091


Requirements
------------

If composer does not fail to install, it is safe to use on your system.
