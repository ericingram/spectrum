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
use net\mkharitonov\spectrum\core\RunResultsBuffer;
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

	public function testShouldBeCreateNewEmptyRunResultsBufferBeforeEveryRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, &$runResultsBuffers){
			$runResultsBuffers[] = $it->getRunResultsBuffer();
		});

		$it->run();
		$this->assertEquals(1, count($runResultsBuffers));
		$this->assertTrue($runResultsBuffers[0] instanceof RunResultsBuffer);
		$this->assertSame(array(), $runResultsBuffers[0]->getResults());

		$it->run();
		$this->assertEquals(2, count($runResultsBuffers));
		$this->assertTrue($runResultsBuffers[1] instanceof RunResultsBuffer);
		$this->assertSame(array(), $runResultsBuffers[1]->getResults());

		$this->assertNotSame($runResultsBuffers[0], $runResultsBuffers[1]);
	}

	public function testShouldBeUnsetReferenceToRunResultsBufferAfterRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it){
			$it->getRunResultsBuffer()->addResult(false);
		});

		$it->run();

		$this->assertNull($it->getRunResultsBuffer());
	}

	public function testShouldBeUnsetResultsInRunResultsBufferAfterRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, &$runResultsBuffer){
			$it->getRunResultsBuffer()->addResult(false);
			$it->getRunResultsBuffer()->addResult(true, 'details foo bar');
			$runResultsBuffer = $it->getRunResultsBuffer();
		});

		$it->run();

		$this->assertSame(array(
			array('result' => false, 'details' => null),
			array('result' => true, 'details' => 'details foo bar'),
		), $runResultsBuffer->getResults());
	}

	public function testShouldBeIgnorePreviousRunResult()
	{
		$it = new SpecItemIt();

		$it->setTestCallback(function() use($it) { $it->getRunResultsBuffer()->addResult(false); });
		$this->assertFalse($it->run());

		$it->setTestCallback(function() use($it) { $it->getRunResultsBuffer()->addResult(true); });
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
		$runningInstanceBackup = SpecItem::getRunningInstance();
		$it = new SpecItemIt();
		$it->setTestCallback(function(){});

		$it->run();

		$this->assertSame($runningInstanceBackup, SpecItem::getRunningInstance());
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
			$it->getRunResultsBuffer()->addResult(true);
			$it->getRunResultsBuffer()->addResult(null);
			$it->getRunResultsBuffer()->addResult(true);
		});

		$this->assertFalse($it->run());
	}

	public function testReturnValue_ShouldBeReturnTrueIfAllResultsInStackIsLikeTrue()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it)
		{
			$it->getRunResultsBuffer()->addResult(true);
			$it->getRunResultsBuffer()->addResult(1);
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