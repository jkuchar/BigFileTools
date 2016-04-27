<?php

require __DIR__ . "/../vendor/autoload.php";

Tester\Environment::setup();

function isWindows() {
	return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
}

define("TESTS_EMPTY_FILE_PATH", __DIR__ . "/temp/emptyfile.tmp"); // 0B
define("TESTS_EMPTY_FILE_WITH_SPACE_PATH", __DIR__ . "/temp/empty - file.tmp"); // 0B  with with space in name
define("TESTS_EMPTY_FILE_WITH_UTF8_PATH", __DIR__ . "/temp/emptyfileěšů指事字.tmp"); // 0B with utf-8
define("TESTS_EMPTY_FILE_SIZE", "0");

define("TESTS_SMALL_FILE_PATH", __DIR__ . "/temp/smallfile.tmp"); // 1M (less then 2^31)
define("TESTS_SMALL_FILE_SIZE", "1048576");

define("TESTS_MEDIUM_FILE_PATH", __DIR__ . "/temp/mediumfile.tmp"); // 2050M (2048M + 2M; between 2^31 and 2^32)
define("TESTS_MEDIUM_FILE_SIZE", "2149580800");

define("TESTS_BIG_FILE_PATH",   __DIR__ . "/temp/bigfile.tmp"); // 4100M (4096M + 4M; more than 2^32)
define("TESTS_BIG_FILE_SIZE",   "4299161600"); // 4096M + 4M = 4100M
