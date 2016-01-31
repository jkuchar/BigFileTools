<?php

require '../vendor/autoload.php';


echo $f = BigFileTools\BigFileTools::fromPath(__FILE__)->getSize()." bytes";
