<?php

require '../vendor/autoload.php';

$bft = new BigFileTools\BigFileTools(/* custom drivers here */);
$file = $bft->getFile(__FILE__);
echo $f = $file->getPath() . " is " . $file->getSize() . " bytes big.\n";
