<?php
/** @testCase */
/**
 * This file is part of the BigFileTools-git.
 * Copyright (c) 2016 Grifart spol. s r.o. (https://grifart.cz)
 */

namespace BigFileTools\Driver;

use BigFileTools\Utils;
use Tester\Assert;
use Tester\Environment;

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

	public function testFileEmptyWithUtf8InName()
	{
		if (isWindows()) {
			// @link http://stackoverflow.com/questions/6467501/php-how-to-create-unicode-filenames
			Environment::skip("PHP does not support UTF-8 in filenames on Windows.");
		}
		parent::testFileEmptyWithUtf8InName();
	}
}

(new NativeSeekDriverTest())->run();
