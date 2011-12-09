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
class BreakOnFirstMatcherFailDisabledTest extends \net\mkharitonov\spectrum\core\asserts\assert\callMatcher\Test
{
	public function testShouldNotBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isExecuted)
		{
			$assert = new Assert(true);
			$assert->beFalse();
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}

	public function testShouldBeAddFalseWithDetailsToResultBufferForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->beFalse();
			$assert->beFalse();

			$assert = new Assert(true);
			$assert->beFalse();
		});

		$results = $resultBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertFalse($results[0]['result']);
		$this->assertFalse($results[1]['result']);
		$this->assertFalse($results[2]['result']);

		$this->assertTrue($results[0]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertTrue($results[1]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertTrue($results[2]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);

		$this->assertAllResultsDetailsDifferent($results);
	}

	public function testShouldBeProvidePropertiesToDetailsForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->beFalse();
			$assert->beReturnSecondArg(0, 'bar');

			$assert = new Assert('foo');
			$assert->beEq('bar');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('beFalse', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(false, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('beReturnSecondArg', $details->getMatcherName());
		$this->assertSame(array(0, 'bar'), $details->getMatcherArgs());
		$this->assertSame('bar', $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[2]['details'];
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('beEq', $details->getMatcherName());
		$this->assertSame(array('bar'), $details->getMatcherArgs());
		$this->assertSame(false, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());
	}

	public function testShouldBeReturnCurrentAssertObject()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);
			$test->assertSame($assert, $assert->beFalse());
			$test->assertSame($assert, $assert->beFalse()->beFalse());
			$test->assertSame($assert, $assert->beFalse()->beTrue()->beFalse());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

/**/

	public function testWithNot_ShouldNotBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isExecuted)
		{
			$assert = new Assert(true);
			$assert->not->beFalse();
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}

	public function testWithNot_ShouldBeAddToResultBufferInvertedResult()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();
			$assert = new Assert(true);
			$assert->not->beTrue();
		});

		$results = $resultBuffer->getResults();
		$this->assertEquals(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
	}

	public function testWithNot_ShouldBeAddFalseWithDetailsToResultBufferForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->not->beTrue();
			$assert->not->beTrue();

			$assert = new Assert(true);
			$assert->not->beTrue();
		});

		$results = $resultBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertFalse($results[0]['result']);
		$this->assertFalse($results[1]['result']);
		$this->assertFalse($results[2]['result']);

		$this->assertTrue($results[0]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertTrue($results[1]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertTrue($results[2]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);

		$this->assertAllResultsDetailsDifferent($results);
	}

	public function testWithNot_ShouldBeProvidePropertiesToDetailsForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->not->beTrue();
			$assert->not->beReturnSecondArg(1, 'bar');

			$assert = new Assert('foo');
			$assert->not->beEq('foo');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('beTrue', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(true, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('beReturnSecondArg', $details->getMatcherName());
		$this->assertSame(array(1, 'bar'), $details->getMatcherArgs());
		$this->assertSame('bar', $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[2]['details'];
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

	public function testWithNot_ShouldBeResetNotAfterCall()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);

			$assert->not->beTrue();
			$test->assertFalse($assert->isNot());

			$assert->beFalse();
			$test->assertFalse($assert->isNot());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

	public function testWithNot_ShouldBeReturnCurrentAssertObject()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);
			$test->assertSame($assert, $assert->not->beFalse());
			$test->assertSame($assert, $assert->not->beFalse()->not->beFalse());
			$test->assertSame($assert, $assert->not->beFalse()->not->beTrue()->not->beFalse());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

/*** Test ware ***/

	protected function createItWithMatchers()
	{
		$it = parent::createItWithMatchers();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		return $it;
	}
}