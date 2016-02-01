<?php
/**
 * @testCase
 */

require __DIR__ . "/../bootstrap.php";

use Tester\Assert;

class Platform32BitTest extends \BigFileTools\BaseTest {

	public function testFileUnder2_31bites() {
		Assert::equal(
			TESTS_SMALL_FILE_SIZE,
			(string) filesize(TESTS_SMALL_FILE_PATH),
			"Failed for file smaller then 2^31 bites"
		);
	}

	public function testFileBetween2_31and2_32_bites() {
		Assert::equal(
			TESTS_MEDIUM_FILE_SIZE,
			sprintf("%u", filesize(TESTS_MEDIUM_FILE_PATH)), // converting unsinged to signed integer
			"Failed for file between 2^31 and 2^32 bites"
		);
	}

	public function testFileLargerThen2_32bites() {
		// file has size 2^32 + 4x 2^20 in size
		// 4x 2^20 = 4 194 304
		// Thanks to 2^32 bit restriction it will look like file has 4mb in size

		\Tester\Assert::equal(
			"4194304", // 4x 2^20
			(string) filesize(TESTS_BIG_FILE_PATH),
			"Failed for file with size over 2^32 bites"
		);
	}

}

(new Platform32BitTest())->run();
