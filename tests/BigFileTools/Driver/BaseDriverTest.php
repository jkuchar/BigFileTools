<?php
/** @testCase */
/**
 * This file is part of the BigFileTools-git.
 * Copyright (c) 2016 Grifart spol. s r.o. (https://grifart.cz)
 */

namespace BigFileTools\Driver;

use Tester\Assert;
use Tester\Environment;
use Tester\TestCase;

abstract class BaseDriverTest extends TestCase
{
	/**
	 * @var ISizeDriver
	 */
	protected $driver;

	protected function setUp()
	{
		parent::setUp();

		try{
			$this->driver = $this->getDriver();
		} catch (PrerequisiteException $e) {
			Environment::skip($e->getMessage());
		}
	}

	/**
	 * @return ISizeDriver
	 */
	abstract protected function getDriver();

	public function testFileEmpty() {
		Assert::equal(
			TESTS_EMPTY_FILE_SIZE,
			(string) $this->driver->getFileSize(TESTS_EMPTY_FILE_PATH),
			"Driver " . get_class($this->getDriver()) . "Failed for file empty file."
		);
	}

	public function testFileSmall_Under2_31bites() {
		Assert::equal(
			TESTS_SMALL_FILE_SIZE,
			(string) $this->driver->getFileSize(TESTS_SMALL_FILE_PATH),
			"Driver " . get_class($this->getDriver()) . "Failed for file smaller then 2^31 bites"
		);
	}

	public function testFileMedium_Between2_31and2_32_bites() {
		Assert::equal(
			TESTS_MEDIUM_FILE_SIZE,
			(string) $this->driver->getFileSize(TESTS_MEDIUM_FILE_PATH),
			"Driver " . get_class($this->getDriver()) . "Failed for file between 2^31 and 2^32 bites"
		);
	}

	public function testFileBig_LargerThen2_32bites() {
		Assert::equal(
			TESTS_BIG_FILE_SIZE,
			(string) $this->driver->getFileSize(TESTS_BIG_FILE_PATH),
			"Driver " . get_class($this->getDriver()) . " failed for file with size over 2^32 bites"
		);
	}
}
