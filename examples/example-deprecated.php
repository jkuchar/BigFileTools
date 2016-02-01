<?php

require '../vendor/autoload.php';
echo $f = BigFileTools::fromPath(__FILE__)->getSize()." bytes";