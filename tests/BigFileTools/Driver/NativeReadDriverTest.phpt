<?php
/** @testCase */
/**
 * This file is part of the BigFileTools-git.
 * Copyright (c) 2016 Grifart spol. s r.o. (https://grifart.cz)
 */

namespace BigFileTools\Driver;

$container = require __DIR__ . "/../../bootstrap.php";

class NativeReadDriverTest extends BaseDriverTest
{
	public function getDriver()
	{
		return new NativeReadDriver();
	}
}

(new NativeReadDriverTest())->run();
