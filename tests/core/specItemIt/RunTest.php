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

namespace net\mkharitonov\spectrum\core\specItemIt;
use net\mkharitonov\spectrum\core\ResultBuffer;
use net\mkharitonov\spectrum\core\SpecItem;
use net\mkharitonov\spectrum\core\SpecItemIt;

require_once dirname(__FILE__) . '/../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class RunTest extends Test
{
	public function testShouldBeCallTestCallback()
	{
		$it = new SpecItemIt();
		$isCalled = false;
		$it->setTestCallback(function() use(&$isCalled){ $isCalled = true; });

		$it->run();

		$this->assertTrue($isCalled);
	}

	public function testShouldBePassWorldAsFirstArgument()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use(&$passedArguments){
			$passedArguments = func_get_args();
		});

		$it->run();

		$this->assertEquals(1, count($passedArguments));
		$this->assertTrue($passedArguments[0] instanceof \net\mkharitonov\spectrum\core\World);
	}

	public function testShouldBePassAdditionalArgumentsToTestCallback()
	{
		$it = new SpecItemIt();
		$it->setAdditionalArguments(array('foo', 'bar'));
		$it->setTestCallback(function() use(&$passedArguments){
			$passedArguments = func_get_args();
		});

		$it->run();

		$this->assertEquals(3, count($passedArguments));

		$this->assertTrue($passedArguments[0] instanceof \net\mkharitonov\spectrum\core\World);
		$this->assertEquals('foo', $passedArguments[1]);
		$this->assertEquals('bar', $passedArguments[2]);
	}

	public function testShouldBeThrowExceptionIfTestCallbackIsNotCallable()
	{
		$it = new SpecItemIt();
		$it->setTestCallback('iAmNotCallableFunctionOhOhOh');

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'callback is not callable', function() use($it) {
			$it->run();
		});
	}

	public function testShouldBeCreateNewEmptyResultBufferBeforeEveryRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, &$resultBuffers){
			$resultBuffers[] = $it->getResultBuffer();
		});

		$it->run();
		$this->assertEquals(1, count($resultBuffers));
		$this->assertTrue($resultBuffers[0] instanceof ResultBuffer);
		$this->assertSame(array(), $resultBuffers[0]->getResults());

		$it->run();
		$this->assertEquals(2, count($resultBuffers));
		$this->assertTrue($resultBuffers[1] instanceof ResultBuffer);
		$this->assertSame(array(), $resultBuffers[1]->getResults());

		$this->assertNotSame($resultBuffers[0], $resultBuffers[1]);
	}

	public function testShouldBeUnsetReferenceToResultBufferAfterRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it){
			$it->getResultBuffer()->addResult(false);
		});

		$it->run();

		$this->assertNull($it->getResultBuffer());
	}

	public function testShouldBeUnsetResultsInResultBufferAfterRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, &$resultBuffer){
			$it->getResultBuffer()->addResult(false);
			$it->getResultBuffer()->addResult(true, 'details foo bar');
			$resultBuffer = $it->getResultBuffer();
		});

		$it->run();

		$this->assertSame(array(
			array('result' => false, 'details' => null),
			array('result' => true, 'details' => 'details foo bar'),
		), $resultBuffer->getResults());
	}

	public function testShouldBeIgnorePreviousRunResult()
	{
		$it = new SpecItemIt();

		$it->setTestCallback(function() use($it) { $it->getResultBuffer()->addResult(false); });
		$this->assertFalse($it->run());

		$it->setTestCallback(function() use($it) { $it->getResultBuffer()->addResult(true); });
		$this->assertTrue($it->run());
	}

	public function testShouldBeSetSelfAsRunningInstanceToSpecItemDuringRun()
	{
		$it = new SpecItemIt();
		$runningInstance = null;
		$it->setTestCallback(function() use(&$runningInstance){
			$runningInstance = SpecItem::getRunningInstance();
		});

		$it->run();

		$this->assertSame($it, $runningInstance);
	}

	public function testShouldBeRestoreRunningInstanceAfterRun()
	{
		$oldRunningInstance = SpecItem::getRunningInstance();
		$it = new SpecItemIt();
		$it->setTestCallback(function(){});

		$it->run();

		$this->assertSame($oldRunningInstance, SpecItem::getRunningInstance());
	}

	public function testShouldBeRestoreRunningInstanceAfterNestedRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use(&$runningInstanceAfterNestedRun)
		{
			$it2 = new SpecItemIt();
			$it2->setTestCallback(function() use($it2) {});
			$it2->run();

			$runningInstanceAfterNestedRun = SpecItem::getRunningInstance();
		});

		$it->run();

		$this->assertSame($it, $runningInstanceAfterNestedRun);
	}

/**/

	public function testReturnValue_ShouldBeReturnFalseIfAnyResultInStackIsLikeFalse()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it)
		{
			$it->getResultBuffer()->addResult(true);
			$it->getResultBuffer()->addResult(null);
			$it->getResultBuffer()->addResult(true);
		});

		$this->assertFalse($it->run());
	}

	public function testReturnValue_ShouldBeReturnTrueIfAllResultsInStackIsLikeTrue()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it)
		{
			$it->getResultBuffer()->addResult(true);
			$it->getResultBuffer()->addResult(1);
		});

		$this->assertTrue($it->run());
	}

	public function testReturnValue_ShouldBeReturnNullIfNoResultsInStack()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it) {});

		$this->assertNull($it->run());
	}

	public function testReturnValue_ShouldBeReturnNullIfTestCallbackNotSet()
	{
		$it = new SpecItemIt();
		$this->assertNull($it->run());
	}
}