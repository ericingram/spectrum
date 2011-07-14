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

namespace net\mkharitonov\spectrum\core\plugin\events\onRun;
use net\mkharitonov\spectrum\core\PluginsManager;
use net\mkharitonov\spectrum\core\SpecItemIt;
use net\mkharitonov\spectrum\core\ResultBuffer;
use net\mkharitonov\spectrum\core\World;

require_once dirname(__FILE__) . '/../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SpecItemItTest extends Test
{
	protected $currentSpecClass = '\net\mkharitonov\spectrum\core\SpecItemIt';
	
	public function testBefore_ShouldBeTriggeredBeforeTestCallbackExecution()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'];
		});

		$spec->run();

		$this->assertEquals('onRunBefore', $triggeredEventsBeforeExecution[0]['name']);

		PluginsManager::unregisterPlugin('foo');
	}

/**/

	public function testAfter_ShouldBeTriggeredAfterTestCallbackExecution()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'];
		});

		$spec->run();

		$this->assertEquals(1, count($triggeredEventsBeforeExecution));
		$this->assertNotEquals('onRunAfter', $triggeredEventsBeforeExecution[0]['name']);

		$this->assertEquals('onRunAfter', \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		PluginsManager::unregisterPlugin('foo');
	}

	public function testAfter_SuccessResult_ShouldBePassResultToArguments()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){
			$spec->getResultBuffer()->addResult(true);
		});

		$spec->run();

		$this->assertSame(array(true), \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['arguments']);

		PluginsManager::unregisterPlugin('foo');
	}

	public function testAfter_FailResult_ShouldBePassResultToArguments()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){
			$spec->getResultBuffer()->addResult(false);
		});

		$spec->run();

		$this->assertSame(array(false), \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['arguments']);

		PluginsManager::unregisterPlugin('foo');
	}

	public function testAfter_EmptyResult_ShouldBePassResultToArguments()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function(){});

		$spec->run();

		$this->assertSame(array(null), \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['arguments']);

		PluginsManager::unregisterPlugin('foo');
	}
}