<?php
/** @testCase */
/**
 * This file is part of the BigFileTools-git.
 * Copyright (c) 2016 Grifart spol. s r.o. (https://grifart.cz)
 */

namespace BigFileTools\Driver;

use Tester\Environment;

$container = require __DIR__ . "/../../bootstrap.php";

class ExecDriverTest extends BaseDriverTest
{
	protected function getDriver()
	{
		return new ExecDriver();
	}

	public function testFileEmptyWithUtf8InName()
	{
		if (isWindows()) {
			// @link http://stackoverflow.com/questions/13332321/php-exec-in-unicode-mode
			Environment::skip("PHP does not support UTF-8 in commandline on Windows.");
		}
		parent::testFileEmptyWithUtf8InName();
	}
}

(new ExecDriverTest())->run();
