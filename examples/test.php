<?php

require 'nette/nette.min.php';
require '../class/BigFileTools.php';


echo $f = BigFileTools::fromPath("nette/nette.min.php")->getSize()." bytes";