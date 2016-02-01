<?php
/** @testCase */
/**
 * This file is part of the BigFileTools-git.
 * Copyright (c) 2016 Grifart spol. s r.o. (https://grifart.cz)
 */

namespace BigFileTools\Driver;

use BigFileTools\Utils;
use Tester\Assert;

$container = require __DIR__ . "/../../bootstrap.php";

class NativeSeekDriverTest extends BaseDriverTest
{
	protected function getDriver()
	{
		return new NativeSeekDriver();
	}

	public function testFileBig_LargerThen2_32bites()
	{
		if(Utils::isPlatformWith32bitInteger()) {
			Assert::exception(function() {
				parent::testFileBig_LargerThen2_32bites();
			}, Exception::class, "Seeking to end of file failed");

		} else {
			parent::testFileBig_LargerThen2_32bites();
		}
	}
}

(new NativeSeekDriverTest())->run();
