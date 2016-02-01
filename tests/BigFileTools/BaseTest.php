<?php
/**
 * This file is part of the BigFileTools-git.
 * Copyright (c) 2016 Grifart spol. s r.o. (https://grifart.cz)
 */

namespace BigFileTools;

use Tester\Environment;
use Tester\TestCase;

class BaseTest extends TestCase
{
	protected function setUp()
	{
		parent::setUp(); // TODO: Change the autogenerated stub

		if((string)PHP_INT_MAX !== "2147483647") { // int max for 32 bit signed integer
			Environment::skip('These tests can run only on platform where PHP has 32-bit integers.');
		}
	}
}
