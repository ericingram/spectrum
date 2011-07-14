<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace net\mkharitonov\spectrum\core\specItemIt\errorHandling;
require_once dirname(__FILE__) . '/../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \net\mkharitonov\spectrum\core\specItemIt\Test
{
	/**
	 * @var \net\mkharitonov\spectrum\core\SpecItemIt
	 */
	protected $it;

	protected function setUp()
	{
		parent::setUp();

		$this->it = new \net\mkharitonov\spectrum\core\SpecItemIt();
		$this->it->errorHandling->setCatchExceptions(false);
		$this->it->errorHandling->setCatchPhpErrors(false);
		$this->it->errorHandling->setBreakOnFirstPhpError(false);
		$this->it->errorHandling->setBreakOnFirstMatcherFail(false);
	}

	public function testShouldBeIgnoreAndSuppressBreakException()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$resultBuffer, $it)
		{
			$resultBuffer = $it->getResultBuffer();
			throw new \net\mkharitonov\spectrum\core\ExceptionBreak();
		});

		$it->run();

		$this->assertEquals(array(), $resultBuffer->getResults());
	}
}