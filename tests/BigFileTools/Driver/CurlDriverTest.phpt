<?php
/** @testCase */
/**
 * This file is part of the BigFileTools-git.
 * Copyright (c) 2016 Grifart spol. s r.o. (https://grifart.cz)
 */

namespace BigFileTools\Driver;

use Tester\Environment;

$container = require __DIR__ . "/../../bootstrap.php";

class CurlDriverTest extends BaseDriverTest
{
	protected function getDriver()
	{
		return new CurlDriver();
	}

	public function testFileEmptyWithUtf8InName()
	{
		if (isWindows()) {
			Environment::skip("CURL does not support UTF-8 on Windows.");
		}
		parent::testFileEmptyWithUtf8InName();
	}
}

(new CurlDriverTest())->run();
