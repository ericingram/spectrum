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

namespace net\mkharitonov\spectrum\core\asserts\assert\callMatcher\matcherReturnTrue;
use net\mkharitonov\spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Test extends \net\mkharitonov\spectrum\core\asserts\assert\callMatcher\Test
{
	public function testShouldNotBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isExecuted)
		{
			$assert = new Assert(true);
			$assert->beTrue();
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}

	public function testShouldBeAddTrueWithDetailsToResultBufferForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->beTrue();
			$assert->beTrue();

			$assert = new Assert(true);
			$assert->beTrue();
		});

		$results = $resultBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertTrue($results[0]['result']);
		$this->assertTrue($results[1]['result']);
		$this->assertTrue($results[2]['result']);

		$this->assertTrue($results[0]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\ResultDetails);
		$this->assertTrue($results[1]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\ResultDetails);
		$this->assertTrue($results[2]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\ResultDetails);

		$this->assertAllResultsDetailsDifferent($results);
	}

	public function testShouldBeProvidePropertiesToDetailsForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->beTrue();
			$assert->beReturnSecondArg(1, 'bar');

			$assert = new Assert('foo');
			$assert->beEq('foo');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('beTrue', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(true, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('beReturnSecondArg', $details->getMatcherName());
		$this->assertSame(array(1, 'bar'), $details->getMatcherArgs());
		$this->assertSame('bar', $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[2]['details'];
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('beEq', $details->getMatcherName());
		$this->assertSame(array('foo'), $details->getMatcherArgs());
		$this->assertSame(true, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());
	}

	public function testShouldBeReturnCurrentAssertObject()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);
			$test->assertSame($assert, $assert->beTrue());
			$test->assertSame($assert, $assert->beTrue()->beTrue());
			$test->assertSame($assert, $assert->beTrue()->beTrue()->beTrue());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

	// TODO: продублировать во всех тестах callMatcher
	public function testShouldBeSupportManyCalls()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled, &$callsArgs)
		{
			$it->matchers->add('beFoo', function($actual, $arg) use(&$callsArgs){
				$callsArgs[] = $arg;
				return true;
			});

			$assert = new Assert(true);
			$assert->beFoo('foo')->beFoo('bar')->beFoo('baz');

			$isCalled = true;
		});

		$this->assertSame(array(
			'foo',
			'bar',
			'baz',
		), $callsArgs);

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
			$assert->not->beFalse();
		});

		$results = $resultBuffer->getResults();
		$this->assertEquals(1, count($results));
		$this->assertSame(true, $results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\ResultDetails);
	}

	public function testWithNot_ShouldBeAddTrueWithDetailsToResultBufferForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->not->beFalse();
			$assert->not->beFalse();

			$assert = new Assert(true);
			$assert->not->beFalse();
		});

		$results = $resultBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertTrue($results[0]['result']);
		$this->assertTrue($results[1]['result']);
		$this->assertTrue($results[2]['result']);

		$this->assertTrue($results[0]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\ResultDetails);
		$this->assertTrue($results[1]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\ResultDetails);
		$this->assertTrue($results[2]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\ResultDetails);

		$this->assertAllResultsDetailsDifferent($results);
	}

	public function testWithNot_ShouldBeProvidePropertiesToDetailsForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->not->beFalse();
			$assert->not->beReturnSecondArg('bar', 0);

			$assert = new Assert('foo');
			$assert->not->beEq('bar');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('beFalse', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(false, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('beReturnSecondArg', $details->getMatcherName());
		$this->assertSame(array('bar', 0), $details->getMatcherArgs());
		$this->assertSame(0, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[2]['details'];
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('beEq', $details->getMatcherName());
		$this->assertSame(array('bar'), $details->getMatcherArgs());
		$this->assertSame(false, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());
	}

	public function testWithNot_ShouldBeProvideNotInvertedMatcherReturnValue()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->not->beFalse();
		});

		$results = $resultBuffer->getResults();
		$this->assertSame(false, $results[0]['details']->getMatcherReturnValue());
	}

	public function testWithNot_ShouldBeResetNotAfterCall()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);

			$assert->not->beFalse();
			$test->assertFalse($assert->isNot());

			$assert->beTrue();
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
			$test->assertSame($assert, $assert->not->beFalse()->not->beFalse()->not->beFalse());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}
}