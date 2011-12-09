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
class BreakOnFirstMatcherFailEnabledTest extends \net\mkharitonov\spectrum\core\asserts\assert\callMatcher\Test
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

	public function testShouldBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$isCalled = true;

			$assert = new Assert(true);
			$assert->beBad();

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
			$assert->beBad();
			$assert->beBad();

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
			$assert->beBad(0, 'bar');

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertTrue($details instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('beBad', $details->getMatcherName());
		$this->assertSame(array(0, 'bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getMatcherException() instanceof \Exception);
		$this->assertSame('I am bad matcher', $details->getMatcherException()->getMessage());
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

	public function testWithNot_ShouldBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$isCalled = true;

			$assert = new Assert(true);
			$assert->not->beBad();

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
			$assert->not->beBad();
			$assert->not->beBad();

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
			$assert->not->beBad(0, 'bar');

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertTrue($details instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('beBad', $details->getMatcherName());
		$this->assertSame(array(0, 'bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getMatcherException() instanceof \Exception);
		$this->assertSame('I am bad matcher', $details->getMatcherException()->getMessage());
	}

/*** Test ware ***/

	protected function createItWithMatchers()
	{
		$it = parent::createItWithMatchers();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		return $it;
	}
}