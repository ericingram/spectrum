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

namespace net\mkharitonov\spectrum\core\asserts\assert\callMatcher\matcherThrowException;
use net\mkharitonov\spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class BreakOnFirstMatcherFailDisabledTest extends \net\mkharitonov\spectrum\core\asserts\assert\callMatcher\Test
{
	public function testCatchExceptionsDisabled_ShouldNotBeCatchExceptions()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$it->errorHandling->setCatchExceptions(false);
			$test->assertThrowException('\Exception', 'I am bad matcher', function()
			{
				$assert = new Assert(true);
				$assert->beBad();
			});

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

	public function testShouldNotBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isExecuted)
		{
			$assert = new Assert(true);
			$assert->beBad();
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
			$assert->beBad();
			$assert->beBad();

			$assert = new Assert('foo');
			$assert->beBad();
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
			$assert->beBad();
			$assert->beBadToo(0, 'bar');

			$assert = new Assert('foo');
			$assert->beBadToo('bar');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('beBad', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getMatcherException() instanceof \Exception);
		$this->assertSame('I am bad matcher', $details->getMatcherException()->getMessage());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('beBadToo', $details->getMatcherName());
		$this->assertSame(array(0, 'bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getMatcherException() instanceof \Exception);
		$this->assertSame('I am bad matcher too', $details->getMatcherException()->getMessage());

		$details = $results[2]['details'];
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('beBadToo', $details->getMatcherName());
		$this->assertSame(array('bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getMatcherException() instanceof \Exception);
		$this->assertSame('I am bad matcher too', $details->getMatcherException()->getMessage());
	}

	public function testShouldBeReturnCurrentAssertObject()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);
			$test->assertSame($assert, $assert->beBad());
			$test->assertSame($assert, $assert->beBad()->beBad());
			$test->assertSame($assert, $assert->beBad()->beTrue()->beBad());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

/**/

	public function testWithNot_CatchExceptionsDisabled_ShouldNotBeCatchExceptions()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$it->errorHandling->setCatchExceptions(false);
			$test->assertThrowException('\Exception', 'I am bad matcher', function()
			{
				$assert = new Assert(true);
				$assert->not->beBad();
			});

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

	public function testWithNot_ShouldNotBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isExecuted)
		{
			$assert = new Assert(true);
			$assert->not->beBad();
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}

	public function testWithNot_ShouldBeIgnoreNotAndAddToResultBufferFalseAnyway()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();
			$assert = new Assert(true);
			$assert->not->beBad();
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
			$assert->not->beBad();
			$assert->not->beBad();

			$assert = new Assert('foo');
			$assert->not->beBad();
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
			$assert->not->beBad();
			$assert->not->beBadToo(0, 'bar');

			$assert = new Assert('foo');
			$assert->not->beBadToo('bar');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('beBad', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getMatcherException() instanceof \Exception);
		$this->assertSame('I am bad matcher', $details->getMatcherException()->getMessage());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('beBadToo', $details->getMatcherName());
		$this->assertSame(array(0, 'bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getMatcherException() instanceof \Exception);
		$this->assertSame('I am bad matcher too', $details->getMatcherException()->getMessage());

		$details = $results[2]['details'];
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('beBadToo', $details->getMatcherName());
		$this->assertSame(array('bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getMatcherException() instanceof \Exception);
		$this->assertSame('I am bad matcher too', $details->getMatcherException()->getMessage());
	}

	public function testWithNot_ShouldBeResetNotAfterCall()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);

			$assert->not->beBad();
			$test->assertFalse($assert->isNot());

			$assert->beBad();
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
			$test->assertSame($assert, $assert->not->beBad());
			$test->assertSame($assert, $assert->not->beBad()->not->beBad());
			$test->assertSame($assert, $assert->not->beBad()->not->beTrue()->not->beBad());

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