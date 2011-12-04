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

namespace net\mkharitonov\spectrum\core\specItemIt\errorHandling\catchPhpErrors\enabled\breakOnFirstPhpError;
use net\mkharitonov\spectrum\core\plugins\Manager;
use net\mkharitonov\spectrum\core\SpecItem;

require_once dirname(__FILE__) . '/../../../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \net\mkharitonov\spectrum\core\specItemIt\errorHandling\catchPhpErrors\enabled\Test
{
	public function testShouldBeReturnFalse()
	{
		$it = $this->it;
		$it->setTestCallback(function() use($it)
		{
			$it->getResultBuffer()->addResult(true);
			trigger_error('');
		});

		$this->assertFalse($it->run());
	}

	public function testErrorLevelSets_ShouldBeCatchErrorsWithProperErrorLevel()
	{
		$it = $this->it;
		$it->errorHandling->setCatchPhpErrors(\E_USER_NOTICE);
		$it->setTestCallback(function()
		{
			trigger_error('', \E_USER_NOTICE);
		});

		$this->assertFalse($it->run());
	}

	public function testErrorLevelSets_ShouldNotBeCatchErrorsWithDifferentErrorLevel()
	{
		$it = $this->it;
		$it->errorHandling->setCatchPhpErrors(\E_USER_ERROR);
		$it->setTestCallback(function() use(&$isExecuted)
		{
			trigger_error('', \E_USER_NOTICE);
			$isExecuted = true;
		});

		$result = $it->run();

		$this->assertNull($result);
		$this->assertTrue($isExecuted);
	}

	public function testShouldBeIgnoreErrorsSuppressedByErrorControlOperators()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$isExecuted, $it)
		{
			@trigger_error('');
			$it->getResultBuffer()->addResult(true);
			$isExecuted = true;
		});

		$result = $it->run();

		$this->assertTrue($result);
		$this->assertTrue($isExecuted);
	}

	public function testShouldBeCatchWorldBuildersErrors()
	{
		$it = $this->it;
		$it->builders->add(function(){ trigger_error(''); });
		$it->setTestCallback(function(){});

		$this->assertFalse($it->run());
	}

	public function testShouldBeCatchWorldDestroyersErrors()
	{
		$it = $this->it;
		$it->setTestCallback(function(){});
		$it->destroyers->add(function(){ trigger_error(''); });

		$this->assertFalse($it->run());
	}

	public function testShouldBeRestoreErrorHandler()
	{
		$it = $this->it;
		$it->setTestCallback(function(){
			trigger_error('');
		});

		set_error_handler('trim');
		$it->run();
		$this->assertEquals('trim', $this->getErrorHandler());
		restore_error_handler();
	}

	public function testShouldBeRestoreErrorHandlerIfPropertyDisabledDuringRun()
	{
		$it = $this->it;
		$it->setTestCallback(function() use($it){
			$it->errorHandling->setCatchPhpErrors(false);
		});

		set_error_handler('trim');
		$it->run();
		$this->assertEquals('trim', $this->getErrorHandler());
		restore_error_handler();
	}

	public function testShouldBeUnsetResultBuffer()
	{
		$it = $this->it;
		$it->setTestCallback(function(){ trigger_error(''); });
		$it->run();

		$this->assertNull($it->getResultBuffer());
	}

	public function testShouldBeRestoreRunningInstance()
	{
		$oldRunningInstance = SpecItem::getRunningInstance();

		$it = $this->it;
		$it->setTestCallback(function(){ trigger_error(''); });
		$it->run();

		$this->assertSame($oldRunningInstance, SpecItem::getRunningInstance());
	}

	public function testShouldBeStopRun()
	{
		$it = $this->it;
		$it->setTestCallback(function(){ trigger_error(''); });
		$it->run();

		$this->assertFalse($it->isRunning());
	}

	public function testShouldBeTriggerEventOnRunAfter()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$it = $this->it;
		$it->setTestCallback(function(){ trigger_error(''); });
		$it->run();
		
		$this->assertEventTriggeredCount(1, 'onRunAfter');

		Manager::unregisterPlugin('foo');
	}
}