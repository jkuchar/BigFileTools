<?php

require '../vendor/autoload.php';

$file = BigFileTools\BigFileTools::createDefault()->getFile(__FILE__);
echo $f = $file->getPath() . " is " . $file->getSize() . " bytes big.\n";
