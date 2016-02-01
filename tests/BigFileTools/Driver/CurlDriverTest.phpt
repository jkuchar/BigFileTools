<?php
/** @testCase */
/**
 * This file is part of the BigFileTools-git.
 * Copyright (c) 2016 Grifart spol. s r.o. (https://grifart.cz)
 */

namespace BigFileTools\Driver;

$container = require __DIR__ . "/../../bootstrap.php";

class CurlDriverTest extends BaseDriverTest
{
	protected function getDriver()
	{
		return new CurlDriver();
	}
}

(new CurlDriverTest())->run();
