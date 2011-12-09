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

namespace net\mkharitonov\spectrum\core\asserts\assert\callMatcher\matcherReturnFalse;
use net\mkharitonov\spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class BreakOnFirstMatcherFailEnabledTest extends \net\mkharitonov\spectrum\core\asserts\assert\callMatcher\Test
{
	public function testShouldBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$isCalled = true;

			$assert = new Assert(true);
			$assert->beFalse();

			$test->fail('Should be break');
		});

		$this->assertTrue($isCalled);
	}

	public function testShouldBeAddFalseWithDetailsToResultBufferOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->beFalse();
			$assert->beFalse();

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$this->assertEquals(1, count($results));
		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
	}

	public function testShouldBeProvidePropertiesToDetailsOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert('foo');
			$assert->beEq('bar');

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertTrue($details instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('beEq', $details->getMatcherName());
		$this->assertSame(array('bar'), $details->getMatcherArgs());
		$this->assertSame(false, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());
	}

/**/

	public function testWithNot_ShouldBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$isCalled = true;

			$assert = new Assert(true);
			$assert->not->beTrue();

			$test->fail('Should be break');
		});

		$this->assertTrue($isCalled);
	}

	public function testWithNot_ShouldBeAddFalseWithDetailsToResultBufferOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->not->beTrue();
			$assert->not->beTrue();

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$this->assertEquals(1, count($results));
		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
	}

	public function testWithNot_ShouldBeProvidePropertiesToDetailsOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert('foo');
			$assert->not->beEq('foo');

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertTrue($details instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('beEq', $details->getMatcherName());
		$this->assertSame(array('foo'), $details->getMatcherArgs());
		$this->assertSame(true, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());
	}

	public function testWithNot_ShouldBeProvideNotInvertedMatcherReturnValue()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->not->beTrue();
		});

		$results = $resultBuffer->getResults();
		$this->assertSame(true, $results[0]['details']->getMatcherReturnValue());
	}

/*** Test ware ***/

	protected function createItWithMatchers()
	{
		$it = parent::createItWithMatchers();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		return $it;
	}
}