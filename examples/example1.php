<?php

require '../class/BigFileTools.php';


echo $f = BigFileTools::fromPath(__FILE__)->getSize()." bytes";