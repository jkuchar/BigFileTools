# BigFileTools #

[![Join the chat at https://gitter.im/jkuchar/BigFileTools](https://badges.gitter.im/jkuchar/BigFileTools.svg)](https://gitter.im/jkuchar/BigFileTools?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Build Status - Linux](https://travis-ci.org/jkuchar/BigFileTools.svg?branch=master)](https://travis-ci.org/jkuchar/BigFileTools)
[![Build status - Windows](https://ci.appveyor.com/api/projects/status/v5af2son33j443xw/branch/master?svg=true)](https://ci.appveyor.com/project/jkuchar/bigfiletools/branch/master)
[![Code Climate](https://codeclimate.com/github/jkuchar/BigFileTools/badges/gpa.svg)](https://codeclimate.com/github/jkuchar/BigFileTools)
[![Latest Stable Version](https://poser.pugx.org/jkuchar/bigfiletools/v/stable)](https://packagist.org/packages/jkuchar/bigfiletools)
[![License](https://poser.pugx.org/jkuchar/bigfiletools/license)](https://packagist.org/packages/jkuchar/bigfiletools)
[![Total Downloads](https://poser.pugx.org/jkuchar/bigfiletools/downloads)](https://packagist.org/packages/jkuchar/bigfiletools)
[![Monthly Downloads](https://poser.pugx.org/jkuchar/bigfiletools/d/monthly)](https://packagist.org/packages/jkuchar/bigfiletools)


This project is collection of hacks that are needed to manipulate files over 2GB in PHP (even on 32-bit systems). Currently there is support for getting **exact file size**. This project started as answer to [stackoverflow question](http://stackoverflow.com/questions/5501451/php-x86-how-to-get-filesize-of-2gb-file-without-external-program). 

Simplest usage:
````php
$file = BigFileTools\BigFileTools::createDefault()->getFile(__FILE__);
echo "This file has " . $file->getSize() . " bytes\n";
````
Will produce output:
````
This file has 176 bytes
````
Please note, that `getSize()` returns [Brick](https://github.com/brick/math)\\[BigInteger](http://brick.io/math/class-Brick.Math.BigInteger.html). This is due to fact that PHP's internal integer can be too small for huge files.

To get *approximate* value of file size you can convert `BigInteger` into `float`. Please note that by doing this you will *loose precision*.

**Tip:** You can configure BigFileTools in any way you want. (no static dependencies included) There is example in example directory prepared for this scenario.

## Will this really work? ##

This project is automatically tested on Linux, Mac OS X and Windows. More about testing in [tests directory](tests). 

## Under the hood ##

To get insight into what is happening we need a little introduction in how numbers are represented in digital world.

The problem lies in the fact that PHP uses 32-bit signed integer on most platforms. PHPx64 for Linux 64-bit version uses 64-bit so you do not need this library there anymore. On the other hand 64-bit version of PHP for 64-bit Windows uses 32-bit integer. Because PHP uses signed integers this means that there is one bit for sign (+-) and rest is used for value.

````
32-bit signed integer max value: +2^31 =             2 147 483 648 ~             2 gigabytes
64-bit signed integer max value: +2^63 = 9 223 372 036 854 775 808 ~ 9 223 372 000 gigabytes
````

To overcome this problem this library uses string representation of numbers which means that only you RAM is limit of number size.

**Caution:** There are tons of non-solutions for this problem. Most of them looks like `sprintf("%u", filesize($file));`. This does NOT solve problem. If just shifts it a little. The `%u` assumes give value as **unsigned** integer. This means that first signing bit is treated also as a value. Unfortunately this means that boundary was just shifted from 2 GB limit to 4 GB. 

Second problem is that standard file manipulation APIs fails with strange errors or returns weird values. That is why BigFileTools has `drivers`. They are by default executed from the fastest to the slowest and unsupported ones are skipped.

### Drivers ###

Currently there is support for *size drivers* - drivers for obtaining file size.

Selecting default drivers and their order of drivers is done based on two factors - availability and speed.

| Driver           | Time (s) ↓          | Runtime requirements | Platform 
| ---------------  | ------------------- | --------------       | ---------
| CurlDriver       | 0.00045299530029297 | CURL extension       | -
| NativeSeekDriver | 0.00052094459533691 | -                    | -
| ComDriver        | 0.0031449794769287  | COM+.NET extension   | Windows only
| ExecDriver       | 0.042937040328979   | exec() enabled       | Windows, Linux, OS X
| NativeRead       | 2.7670161724091     | -                    | -

In default configuration size drivers are ordered by speed and unavailable ones are skipped. This means that in default configuration you do not need to worry about compatibility.

Requirements
------------
Please follow [Composer](https://getcomposer.org/) requirements.

To speed things up (e.g. in production) **I recommend installing CURL extension** which enables you to use the fastest driver.
