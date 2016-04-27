<?php
/** @testCase */
/**
 * This file is part of the BigFileTools-git.
 * Copyright (c) 2016 Grifart spol. s r.o. (https://grifart.cz)
 */

namespace BigFileTools\Driver;

use Tester\Environment;

$container = require __DIR__ . "/../../bootstrap.php";

class NativeReadDriverTest extends BaseDriverTest
{
	protected function getDriver()
	{
		return new NativeReadDriver();
	}

	public function testFileEmptyWithUtf8InName()
	{
		if (isWindows()) {
			// @link http://stackoverflow.com/questions/6467501/php-how-to-create-unicode-filenames
			Environment::skip("PHP does not support UTF-8 in filenames on Windows.");
		}
		parent::testFileEmptyWithUtf8InName();
	}
}

(new NativeReadDriverTest())->run();
