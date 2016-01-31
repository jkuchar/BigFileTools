<?php

require '../class/BigFileTools.php';

$size = BigFileTools::fromPath(__FILE__)->getSize();
var_dump($size);
echo "Example files size is " . $size . " bytes\n";