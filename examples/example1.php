<?php

require '../vendor/autoload.php';
use BigFileTools\BigFileTools;

$file = BigFileTools::createDefault()->getFile(__FILE__);
echo $f = $file->getPath() . " is " . $file->getSize() . " bytes big.\n";
