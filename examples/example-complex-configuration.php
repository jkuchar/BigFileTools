<?php

require '../vendor/autoload.php';

$listOfDrivers = [
	\BigFileTools\Driver\ExecDriver::class,
	\BigFileTools\Driver\ComDriver::class,
	\BigFileTools\Driver\CurlDriver::class,
];

// Create from will makes sure that drivers that are not available on give platform will be skipped
// This uses BestEffortAggregateSizeDriver internally
$bft = BigFileTools\BigFileTools::createFrom($listOfDrivers);

// It is same as:
$driver = new \BigFileTools\Driver\BestEffortAggregateSizeDriver($listOfDrivers);
$bft = new \BigFileTools\BigFileTools($driver);

$file = $bft->getFile(__FILE__);
echo $f = $file->getPath() . " is " . $file->getSize() . " bytes big.\n";
