<?php

require __DIR__ . "/../vendor/autoload.php";

Tester\Environment::setup();

define("TESTS_EMPTY_FILE_PATH", __DIR__ . "/temp/emptyfile.tmp"); // 0B
define("TESTS_EMPTY_FILE_SIZE", "0");

define("TESTS_SMALL_FILE_PATH", __DIR__ . "/temp/smallfile.tmp"); // 1M (less then 2^31)
define("TESTS_SMALL_FILE_SIZE", "1048576");

define("TESTS_MEDIUM_FILE_PATH", __DIR__ . "/temp/mediumfile.tmp"); // 2050M (2048M + 2M; between 2^31 and 2^32)
define("TESTS_MEDIUM_FILE_SIZE", "2149580800");

define("TESTS_BIG_FILE_PATH",   __DIR__ . "/temp/bigfile.tmp"); // 4100M (4096M + 4M; more than 2^32)
define("TESTS_BIG_FILE_SIZE",   "4299161600"); // 4096M + 4M = 4100M
